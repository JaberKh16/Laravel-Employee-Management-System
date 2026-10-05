<?php

namespace App\Http\Controllers\Backend;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Enums\ActiveStatus;
use App\Http\Enums\EmployeeStatus;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Throwable;
use App\Http\Helpers\Templates\UserTemplates;

class UserController extends Controller
{
    /**
     * Shared template helper — resolved once per controller instance.
     */
    protected UserTemplates $templates;



    public function __construct()
    {
        $this->templates = new UserTemplates();
        // $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:user-create', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }




    public function index(Request $request)
    {
        try {
            $users = $this->templates
                ->buildFilteredQuery($request)
                ->paginate(25)
                ->withQueryString();

            $downloadOptions = $this->templates->downloadOptionsForExport();
            $columns = $this->templates->columnsToSelect();

            $actionButtons = $users->getCollection()
                ->mapWithKeys(fn(User $user) => [
                    $user->id => $this->templates->actionButtons($user),
                ])
                ->all();   // ← convert to plain array

            return view('admin.pages.Users.index', compact(
                'users',
                'downloadOptions',
                'columns',
                'actionButtons',
            ));

        } catch (Throwable $e) {
            Log::error('Failed to load users index', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Unable to load users.');
        }
    }



    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');

            // Reuse the same query builder logic
            $users = $this->templates
                ->buildFilteredQuery($request)
                ->get();

            $filename = 'users-' . now()->format('Y-m-d_His');

            return match ($format) {
                'csv' => Excel::download(new UsersExport($users), "{$filename}.csv"),
                'xlsx' => Excel::download(new UsersExport($users), "{$filename}.xlsx"),
                'pdf' => PDF::loadView('exports.users', compact('users'))
                    ->download("{$filename}.pdf"),
                'json' => response()->json($users),
                default => back()->with('error', 'Invalid export format.'),
            };

        } catch (Throwable $e) {
            Log::error('Failed to export users', [
                'error' => $e->getMessage(),
                'format' => $request->get('format'),
            ]);

            return back()->with('error', 'Unable to export users.');
        }
    }


    public function create()
    {
        try {
            $roles = Role::select('id', 'name')->orderBy('name')->get();

            return view('admin.pages.Users.create', compact('roles'));

        } catch (Throwable $e) {
            Log::error('Failed to load user create form', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Unable to open the create form.');
        }
    }


    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // 1) Create the User (only columns that exist on the users table).
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => ActiveStatus::Active->value,
            ]);

            // 2) Create the Profile with the names + optional fields.
            //    Profile is the table that owns first_name / last_name.
            $user->profile()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
            ]);

            // 3) Assign roles (Spatie). Handle both array and single string.
            $roles = $request->input('roles', []);
            if (!empty($roles)) {
                $user->syncRoles($roles);
            }

            DB::commit();

            notify()->success('User Created Successfully!!!', 'Success', 'topRight');

            return redirect()
                ->route('users.index')
                ->with('success', 'User Created Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to create user', [
                'payload' => $request->except(['password', 'password_confirmation']),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }


    public function show(User $user)
    {
        // $user->load(['profile.city', 'profile.state', 'profile.country', 'employee.department', 'parent.profile']);
        // return response()->json(['data' => $this->transformUser($user)]);
    }


    public function edit(User $user)
    {
        try {
            $roles = Role::select('id', 'name')->orderBy('name')->get();
            $userRole = $user->roles->pluck('name')->all();


            // Ensure a profile exists so the form's old() fallbacks work.
            $user->loadMissing('profile');

            return view('admin.pages.Users.edit', compact(
                'user',
                'roles',
                'userRole'
            ));

        } catch (Throwable $e) {
            Log::error('Failed to load user edit form', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Unable to open the edit form.');
        }
    }


    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            // 1) Update User table columns only.
            $userData = [
                'username' => $request->username,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // 2) Update or create the Profile.
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'birthdate' => $request->birthdate,
                ]
            );

            // 3) Sync roles.
            $roles = $request->input('roles', []);
            if (!empty($roles)) {
                $user->syncRoles($roles);
            }

            DB::commit();

            return redirect()
                ->route('users.index')
                ->with('success', 'User Updated Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update user', [
                'user_id' => $user->id,
                'payload' => $request->except(['password', 'password_confirmation']),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }


    public function destroy(User $user)
    {
        try {
            // Prevent self-deletion.
            if (Auth::id() === $user->id) {
                return redirect()
                    ->route('users.index')
                    ->with('error', "You can't delete your own account.");
            }

            DB::beginTransaction();

            // Delete the profile first (or rely on FK cascade).
            $user->profile()?->delete();

            $user->delete();

            DB::commit();

            return redirect()
                ->route('users.index')
                ->with('success', 'User Deleted Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to delete user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Failed to delete user. Please try again.');
        }
    }


    public function profile()
    {
        $user = Auth::user();
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        $states = State::select('id', 'name')->orderBy('name')->get();
        $cities = City::select('id', 'name')->orderBy('name')->get();

        return view('admin.pages.Users.profile', compact('user', 'countries', 'states', 'cities'));
    }


    public function profileUpdate(ProfileUpdateRequest $request)
    {
        try {
            $user = $request->user();

            DB::beginTransaction();

            /* ---------- 1. User table ---------- */
            $userData = $request->validatedUserFields();

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->input('password'));
            }

            $user->update($userData);

            /* ---------- 2. Profile table ---------- */
            $profileData = $request->validatedProfileFields();

            // Avatar upload — delete old file first.
            if ($request->hasFile('avatar')) {
                if ($user->profile?->avatar && Storage::disk('public')->exists($user->profile->avatar)) {
                    Storage::disk('public')->delete($user->profile->avatar);
                }

                $profileData['avatar'] = $request->file('avatar')
                    ->store('avatars/' . $user->id, 'public');
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            DB::commit();

            return redirect()
                ->route('users.profile')
                ->with('success', 'Profile Updated Successfully!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update profile', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }


    /* ══════════════════════════════════════════════════════════════
       MAKE EMPLOYEE — convert user to employee
       ══════════════════════════════════════════════════════════════ */
    public function makeEmployee(Request $request, User $user)
    {
        try {
            DB::beginTransaction();

            if ($user->employee) {
                return back()->with('info', "{$user->username} is already an employee.");
            }

            Employee::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id ?? null,
                'country_id' => $request->country_id ?? null,
                'city_id' => $request->city_id ?? null,
                'state_id' => $request->state_id ?? null,
                'address' => optional($user->profile)->address,
                'zip_code' => optional($user->profile)->zip_code,
                'birthdate' => optional($user->profile)->birthdate,
                'date_hired' => now(),
                'status' => EmployeeStatus::Active->value,
            ]);

            DB::commit();

            return back()->with('success', "{$user->username} is now an employee.");

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('makeEmployee failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Unable to convert user to employee.');
        }
    }

    /* ══════════════════════════════════════════════════════════════
       PROFILE — JSON endpoint (optional fallback)
       ══════════════════════════════════════════════════════════════ */
    public function profileShow(User $user)
    {
        $user->load(['profile', 'employee.department', 'role']);

        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => optional($user->profile)->first_name,
            'last_name' => optional($user->profile)->last_name,
            'phone' => optional($user->profile)->phone,
            'address' => optional($user->profile)->address,
            'birthdate' => optional($user->profile)->birthdate,
            'employee' => $user->employee ? [
                'department' => optional($user->employee->department)->name,
                'hire_date' => $user->employee->date_hired,
                'status' => $user->employee->status?->value,
            ] : null,
        ]);
    }


    // ═══════════════════════════════════════════════════════════
    // SHOW PROFILE (for modal)
    // GET /admin/users/{user}/profile/modal
    // ═══════════════════════════════════════════════════════════
    public function showProfile(User $user): JsonResponse
    {
        $user->load([
            'profile.city',
            'profile.state',
            'profile.country',
            'employee.department',
            'parent.profile',
        ]);

        return response()->json([
            'data' => $this->transformProfile($user),
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE PROFILE (from modal edit mode)
    // PUT /admin/users/{user}/profile/modal
    // ═══════════════════════════════════════════════════════════
    public function updateProfile(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            // ── User-level ─────────────────────────────────
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            // ── Profile: Personal ──────────────────────────
            'first_name'  => ['nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['nullable', 'string', 'max:100'],
            'birthdate'   => ['nullable', 'date', 'before:today'],
            'gender'      => ['nullable', Rule::in(['male', 'female', 'other'])],

            // ── Profile: Contact ───────────────────────────
            'phone'    => ['nullable', 'string', 'max:30'],
            'website'  => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'twitter'  => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'address'  => ['nullable', 'string', 'max:500'],

            // ── Profile: Location ──────────────────────────
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id'   => ['nullable', 'exists:states,id'],
            'city_id'    => ['nullable', 'exists:cities,id'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            // Update user-level field
            $user->update([
                'email' => $validated['email'],
            ]);

            // Extract profile-only fields
            $profileData = collect($validated)->only([
                'first_name', 'middle_name', 'last_name', 'birthdate', 'gender',
                'phone', 'website', 'linkedin', 'twitter', 'zip_code', 'address',
                'country_id', 'state_id', 'city_id',
            ])->toArray();

            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $user->profile()->create($profileData);
            }
        });

        $user->refresh()->load([
            'profile.city',
            'profile.state',
            'profile.country',
            'employee.department',
            'parent.profile',
        ]);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data'    => $this->transformProfile($user),
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // SHARED TRANSFORMER
    // ═══════════════════════════════════════════════════════════
    protected function transformProfile(User $user): array
    {
        $p = $user->profile;

        return [
            // ── Identity ───────────────────────────────────
            'id'        => $user->id,
            'username'  => $user->username,
            'email'     => $user->email,
            'full_name' => $p?->full_name ?? $user->username,
            'initials'  => $p?->initials  ?? strtoupper(substr($user->username ?? 'U', 0, 2)),

            // ── Status (enum-safe) ─────────────────────────
            'status' => is_object($user->status)
                ? strtolower($user->status->label())
                : strtolower((string) ($user->status ?? 'unknown')),

            // ── Timestamps ─────────────────────────────────
            'created_at_human' => $user->created_at?->format('M d, Y · H:i'),
            'updated_at_human' => $user->updated_at?->diffForHumans(),

            // ── Profile ────────────────────────────────────
            'profile' => $p ? [
                'first_name'    => $p->first_name,
                'middle_name'   => $p->middle_name,
                'last_name'     => $p->last_name,
                'birthdate'     => $p->birthdate?->format('M d, Y'),
                'birthdate_raw' => $p->birthdate?->format('Y-m-d'),
                'gender'        => $p->gender ? ucfirst($p->gender) : null,
                'gender_raw'    => $p->gender,
                'age'           => $p->birthdate?->age,
                'phone'         => $p->phone,
                'website'       => $p->website,
                'linkedin'      => $p->linkedin,
                'twitter'       => $p->twitter,
                'zip_code'      => $p->zip_code,
                'address'       => $p->address,
                'country_id'    => $p->country_id,
                'state_id'      => $p->state_id,
                'city_id'       => $p->city_id,
                'city'          => ['name' => $p->city?->name],
                'state'         => ['name' => $p->state?->name],
                'country'       => ['name' => $p->country?->name],
            ] : null,

            // ── Employee ───────────────────────────────────
            'employee' => $user->employee ? [
                'employee_id' => $user->employee->employee_id,
                'designation' => $user->employee->designation,
                'hire_date'   => optional($user->employee->hire_date)->format('M d, Y'),
                'status'      => is_object($user->employee->status)
                    ? ($user->employee->status->label() ?? $user->employee->status->value)
                    : $user->employee->status,
                'department'  => ['name' => $user->employee->department?->name],
            ] : null,

            // ── Parent ─────────────────────────────────────
            'parent' => $user->parent ? [
                'full_name' => $user->parent->profile?->full_name ?? $user->parent->username,
            ] : null,
        ];
    }
}