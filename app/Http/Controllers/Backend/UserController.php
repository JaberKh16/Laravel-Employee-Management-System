<?php

namespace App\Http\Controllers\Backend;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Enums\ActiveStatus;
use App\Http\Enums\EmployeeStatus;
use App\Http\Helpers\Templates\UserTemplates;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Throwable;

class UserController extends Controller
{
    /**
     * Shared template helper — resolved once per controller instance.
     */
    protected UserTemplates $templates;

    /**
     * Filesystem manager — replaces the Storage facade.
     */
    protected FilesystemManager $filesystem;

    public function __construct()
    {
        $this->templates = new UserTemplates();
        $this->filesystem = app(FilesystemManager::class);

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
                ->all();

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

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => ActiveStatus::Active->value,
            ]);

            $user->profile()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
            ]);

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

            $userData = [
                'username' => $request->username,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

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
            if (Auth::id() === $user->id) {
                return redirect()
                    ->route('users.index')
                    ->with('error', "You can't delete your own account.");
            }

            DB::beginTransaction();

            // Delete the profile first (or rely on FK cascade).
            if ($user->profile) {
                $user->profile->delete();
            }

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
                $disk = $this->filesystem->disk('public');

                if ($user->profile?->avatar && $disk->exists($user->profile->avatar)) {
                    $disk->delete($user->profile->avatar);
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
        $user->load(['profile', 'employee.department', 'roles']);

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
}