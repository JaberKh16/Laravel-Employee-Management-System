<?php

namespace App\Http\Controllers\Backend;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Enums\ActiveStatus;
use App\Http\Enums\EmployeeStatus;
use App\Http\Helpers\Notifications\CustomPushNotification;
use App\Http\Helpers\Templates\UserTemplates;
use App\Http\Requests\ImportUsersRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Traits\ValidateRequestInput;
use App\Imports\UsersImport;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Profile;
use App\Models\State;
use App\Models\User;
use App\Models\JobProfile;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Database\QueryException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;
use lluminate\Support\Facades\Route;
use Throwable;

class UserController extends Controller
{
    use ValidateRequestInput;
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
        // $this->notificationService = new NotificationService();


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
            $statusOptions = $this->templates->getStatus();

            $actionButtons = $users->getCollection()
                ->mapWithKeys(fn(User $user) => [
                    $user->id => $this->templates->actionButtons($user),
                ])
                ->all();

            // Roles for the filter dropdown
            $roles = $this->templates->getRoles();

            $filterCriterias = $this->templates->filterCriterias();

            return view('admin.pages.Users.index', compact(
                'users',
                'downloadOptions',
                'columns',
                'actionButtons',
                'statusOptions',
                'roles',
                'filterCriterias'
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
            $status = ActiveStatus::values();

            return view('admin.pages.Users.template', compact('roles', 'statusOptions'));

        } catch (Throwable $e) {
            Log::error('Failed to load user create form', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Unable to open the create form.');
        }
    }


    public function store(UserStoreRequest $request, CustomPushNotification $notifications)
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

            // add ehr ethe helper
            // Send the user-created notification
            // $notifications->send('user.created', $user, [
            //     'roles' => $roles,
            // ]);
            $notifications->send('created', $user);


            DB::commit();

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


    // public function edit(User $user)
    // {
    //     try {
    //         $roles = Role::select('id', 'name')->orderBy('name')->get();
    //         $userRole = $user->roles->pluck('name')->all();

    //         $user->loadMissing('profile');

    //         return view('admin.pages.Users.edit', compact(
    //             'user',
    //             'roles',
    //             'userRole'
    //         ));

    //     } catch (Throwable $e) {
    //         Log::error('Failed to load user edit form', [
    //             'user_id' => $user->id,
    //             'error' => $e->getMessage(),
    //         ]);

    //         return redirect()
    //             ->route('users.index')
    //             ->with('error', 'Unable to open the edit form.');
    //     }
    // }

    public function edit(Request $request, User $user)
    {
        try {
            if ($request->wantsJson()) {
                return response()->json([
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'profile' => [
                        'first_name' => $user->profile?->first_name,
                        'last_name' => $user->profile?->last_name,
                    ],
                    'role_ids' => $user->roles->pluck('id')->values(),
                    'update_url' => route('users.update', $user),
                ]);
            }

            $roles = Role::select('id', 'name')->orderBy('name')->get();
            $statusOptions = ActiveStatus::values();
            $userRole = $user->roles;

            return view('admin.pages.Users.template', compact(
                'user',
                'roles',
                'statusOptions',
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


    public function update(UserUpdateRequest $request, User $user, CustomPushNotification $notifications)
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
            $notifications->send('updated', $user);

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

    private function splitFullName(string $full): array
    {
        $parts = preg_split('/\s+/', trim($full), 2);
        return [$parts[0] ?? '', $parts[1] ?? ''];
    }


    /* ══════════════════════════════════════════════════════════════
       MAKE EMPLOYEE — convert user to employee
       ══════════════════════════════════════════════════════════════ */
    /**
     * GET /admin/users/{user}/make-employee/data
     * Return user + profile data for the Make Employee modal.
     */
    public function makeEmployeeData(User $user): JsonResponse
    {
        $data = $this->templates->configureEmployeeForm(
            user: $user,
            options: [
                'genders' => ['male', 'female', 'other'],
            ],
            action: route('users.make-employee', $user),
        );

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }


    public function makeEmployee(Request $request, User $user)
    {
        $validated = $request->validate([
            // profile / user
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before_or_equal:today'],
            'gender' => ['required', 'in:male,female,other'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'address' => ['nullable', 'string', 'max:1000'],

            // employee
            'department_id' => ['required', 'exists:departments,id'],
            'branch_id' => ['nullable', 'exists:branch,id'],
            'hired_date' => ['nullable', 'date'],

            // job profile (optional on create)
            'job_profile_id' => ['nullable', 'integer', 'exists:job_profiles,id'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:150'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'nationality' => ['nullable', 'string', 'max:80'],
            'marital_status' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        // ── Guard: already an employee? ──
        if ($user->employee()->exists()) {
            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => "{$user->username} is already an employee.",
                ], 422)
                : back()->with('info', "{$user->username} is already an employee.");
        }

        try {
            DB::beginTransaction();

            // ── 1) Resolve or create the JobProfile first ──
            $jobProfile = null;

            if (!empty($validated['job_profile_id'])) {
                $jobProfile = JobProfile::findOrFail($validated['job_profile_id']);
            } else {
                $jobProfile = JobProfile::create([
                    'user_id' => $user->id,
                    'department_id' => $validated['department_id'] ?? null,
                    'branch_id' => $validated['branch_id'] ?? null,
                    'employment_type' => $validated['employment_type'] ?? null,
                    'designation' => $validated['designation'] ?? null,
                    'basic_salary' => $validated['basic_salary'] ?? null,
                    'allowance' => $validated['allowance'] ?? null,
                    'currency' => $validated['currency'] ?? 'USD',
                    'national_id' => $validated['national_id'] ?? null,
                    'passport_number' => $validated['passport_number'] ?? null,
                    'nationality' => $validated['nationality'] ?? null,
                    'marital_status' => $validated['marital_status'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);
            }

            // ── 2) Create Employee (with job_profile_id) ──
            $employee = $user->employee()->create([
                'job_profile_id' => $jobProfile->id,
                'department_id' => $validated['department_id'],
                'country_id' => $validated['country_id'] ?? null,
                'state_id' => $validated['state_id'] ?? null,
                'city_id' => $validated['city_id'] ?? null,
                'hired_date' => $validated['hired_date'] ?? now(),
                'status' => EmployeeStatus::Active->value,
                'emp_code' => $this->generateEmployeeCode(),
            ]);

            // ── 3) Assign the Employee role (replaces existing) ──
            $user->syncRoles(['employee']);

            // ── 4) Update / create user profile ──
            [$firstName, $lastName] = $this->splitFullName($validated['name']);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'birthdate' => $validated['birthdate'],
                    'gender' => $validated['gender'],
                    'zip_code' => $validated['zip_code'] ?? null,
                    'country_id' => $validated['country_id'] ?? null,
                    'state_id' => $validated['state_id'] ?? null,
                    'city_id' => $validated['city_id'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]
            );

            $user->update([
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            DB::commit();

            // ── 5) Reload for fresh relations ──
            $user->refresh()->load([
                'profile',
                'employee.department',
                'employee.jobProfile',
                'roles',
            ]);

            return $request->expectsJson()
                ? response()->json([
                    'success' => true,
                    'message' => "{$user->username} is now an employee.",
                    'employee' => $user->employee,
                    'user' => $user,
                    'roles' => $user->getRoleNames(),
                ])
                : back()->with('success', "{$user->username} is now an employee.");

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('makeEmployee failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Unable to convert user to employee.',
                    'error' => $e->getMessage(),
                ], 500)
                : back()->with('error', 'Unable to convert user to employee.');
        }
    }



    public function updateEmployee(Request $request, User $user): JsonResponse
    {
        $employee = $user->employee;
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an employee.',
            ], 404);
        }

        $validated = $request->validate([
            // profile / user
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before_or_equal:today'],
            'gender' => ['required', 'in:male,female,other'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'address' => ['nullable', 'string', 'max:1000'],

            // employee
            'department_id' => ['required', 'exists:departments,id'],
            'branch_id' => ['nullable', 'exists:branch,id'],

            // job profile
            'job_profile_id' => ['nullable', 'integer', 'exists:job_profiles,id'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:150'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'nationality' => ['nullable', 'string', 'max:80'],
            'marital_status' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            DB::transaction(function () use ($user, $employee, $validated) {
                // ── 1) Employee core fields ──
                $employee->update([
                    'department_id' => $validated['department_id'],
                    'branch_id' => $validated['branch_id'] ?? null,
                    'country_id' => $validated['country_id'] ?? null,
                    'state_id' => $validated['state_id'] ?? null,
                    'city_id' => $validated['city_id'] ?? null,
                ]);

                // ── 2) Job profile (create if missing) ──
                $jobProfile = $employee->jobProfile
                    ?? JobProfile::firstOrCreate(
                        ['user_id' => $user->id],
                        ['department_id' => $validated['department_id']]
                    );

                $jobProfile->fill([
                    'department_id' => $validated['department_id'] ?? $jobProfile->department_id,
                    'branch_id' => $validated['branch_id'] ?? $jobProfile->branch_id,
                    'employment_type' => $validated['employment_type'] ?? $jobProfile->employment_type,
                    'designation' => $validated['designation'] ?? $jobProfile->designation,
                    'basic_salary' => $validated['basic_salary'] ?? $jobProfile->basic_salary,
                    'allowance' => $validated['allowance'] ?? $jobProfile->allowance,
                    'currency' => $validated['currency'] ?? $jobProfile->currency,
                    'national_id' => $validated['national_id'] ?? $jobProfile->national_id,
                    'passport_number' => $validated['passport_number'] ?? $jobProfile->passport_number,
                    'nationality' => $validated['nationality'] ?? $jobProfile->nationality,
                    'marital_status' => $validated['marital_status'] ?? $jobProfile->marital_status,
                    'phone' => $validated['phone'] ?? $jobProfile->phone,
                    'emergency_contact_name' => $validated['emergency_contact_name'] ?? $jobProfile->emergency_contact_name,
                    'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? $jobProfile->emergency_contact_phone,
                    'postal_code' => $validated['postal_code'] ?? $jobProfile->postal_code,
                    'notes' => $validated['notes'] ?? $jobProfile->notes,
                ]);
                $jobProfile->save();

                // ── 3) Ensure employee ↔ job profile link ──
                if ($employee->job_profile_id !== $jobProfile->id) {
                    $employee->update(['job_profile_id' => $jobProfile->id]);
                }

                // ── 4) User profile (split name → first/last) ──
                [$firstName, $lastName] = $this->splitFullName($validated['name']);

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'birthdate' => $validated['birthdate'],
                        'gender' => $validated['gender'],
                        'zip_code' => $validated['zip_code'] ?? null,
                        'country_id' => $validated['country_id'] ?? null,
                        'state_id' => $validated['state_id'] ?? null,
                        'city_id' => $validated['city_id'] ?? null,
                        'address' => $validated['address'] ?? null,
                    ]
                );

                $user->update([
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // ── 5) Sync role: employee should also have 'employee' ──
                if (!$user->hasRole('employee')) {
                    $user->assignRole('employee');
                }
            });

            return response()->json([
                'success' => true,
                'message' => "{$user->username} updated.",
                'data' => [
                    'employee' => $user->fresh()->employee,
                    'job_profile' => $user->fresh()->employee?->jobProfile,
                ],
                'redirect_url' => \Illuminate\Support\Facades\Route::has('users.index')
                    ? route('users.index')
                    : url('/admin/users'),
            ]);

        } catch (Throwable $e) {
            Log::error('updateEmployee failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate a unique employee code like EMP-0001, EMP-0002, ...
     */
    protected function generateEmployeeCode(): string
    {
        $last = Employee::query()
            ->where('emp_code', 'like', 'EMP-%')
            ->orderByDesc('id')
            ->value('emp_code');

        $nextNumber = 1;
        if ($last && preg_match('/EMP-(\d+)/', $last, $m)) {
            $nextNumber = ((int) $m[1]) + 1;
        }

        // Safety: loop until unique (in case of gaps or races)
        do {
            $code = 'EMP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $exists = Employee::where('emp_code', $code)->exists();
            $nextNumber++;
        } while ($exists);

        return $code;
    }

    public function updateStatus(Request $request, User $user)
    {
        $allowedNames = array_map(fn($s) => $s->name, ActiveStatus::cases());

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in($allowedNames)],
        ]);

        $status = collect(ActiveStatus::cases())
            ->firstWhere('name', $validated['status']);

        if (!$status) {
            return response()->json([
                'message' => 'Invalid status.',
                'errors' => ['status' => ['Invalid status value.']],
            ], 422);
        }

        $user->update(['status' => $status]);

        return response()->json([
            'status' => $status->value,   // 0..4
            'status_name' => $status->name,    // "Active" | "Inactive" | ... | "Banned"
            'label' => $status->label(),
            'message' => 'User status updated successfully.',
        ]);
    }


    /* ══════════════════════════════════════════════════════════════
        IMPORT
    ══════════════════════════════════════════════════════════════ */
    public function importForm()
    {
        $roles = Role::query()->orderBy('name')->pluck('name');

        return view('admin.pages.Users.import', compact('roles'));
    }

    public function importStore(ImportUsersRequest $request)
    {
        try {
            $import = new UsersImport(
                defaultRole: $request->input('default_role'),
                updateExisting: $request->boolean('update_existing'),
                skipHeader: $request->boolean('skip_header', true),
            );

            Excel::import($import, $request->file('file'));

            $summary = $import->summary();

            Log::info('[users-import] Completed', [
                'by' => $request->user()?->id,
                'summary' => $summary,
            ]);

            // Nothing imported at all → send back to import page with summary
            if ($summary['created'] === 0 && $summary['updated'] === 0) {
                return redirect()
                    ->route('users.import')          // ← was admin.users.import
                    ->with('warning', 'Import finished, but no users were created or updated.')
                    ->with('import_summary', $summary);
            }

            return redirect()
                ->route('users.index')               // ← was admin.users.index
                ->with('success', sprintf(
                    'Import complete: %d created, %d updated, %d skipped, %d errors.',
                    $summary['created'],
                    $summary['updated'],
                    $summary['skipped'],
                    $summary['errors'],
                ))
                ->with('import_summary', $summary);

        } catch (Throwable $e) {
            Log::error('[users-import] Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function importTemplate(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'username',
                'email',
                'password',
                'first_name',
                'last_name',
                'phone',
                'status',
                'role',
            ]);

            fputcsv($out, [
                'johndoe',
                'john@example.com',
                'Secret123!',
                'John',
                'Doe',
                '+1 555 0100',
                'Active',
                'user',
            ]);

            fclose($out);
        }, 'users-import-template.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-import-template.csv"',
        ]);
    }

    public function bulkRole(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:users,id'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $ids = collect($validated['ids'])
            ->reject(fn($id) => (int) $id === Auth::id())
            ->values()
            ->all();

        if (empty($ids)) {
            return response()->json([
                'message' => 'You cannot change your own role.',
                'errors' => ['ids' => ['You cannot change your own role.']],
            ], 422);
        }

        $role = Role::where('name', $validated['role'])->firstOrFail();

        DB::transaction(function () use ($ids, $role) {
            User::whereIn('id', $ids)
                ->get()
                ->each(fn(User $u) => $u->syncRoles([$role->name]));
        });

        return response()->json([
            'role_name' => $role->name,
            'updated' => count($ids),
            'message' => "Assigned role \"{$role->name}\" to " . count($ids) . ' user(s).',
        ]);
    }

    /* ══════════════════════════════════════════════════════════════
       BULK STATUS 
       ══════════════════════════════════════════════════════════════ */
    public function bulkStatus(Request $request)
    {
        $allowedNames = array_map(fn($s) => $s->name, ActiveStatus::cases());

        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:users,id'],
            'status' => ['required', 'string', Rule::in($allowedNames)],
        ]);

        $status = collect(ActiveStatus::cases())
            ->firstWhere('name', $validated['status']);

        if (!$status) {
            return response()->json([
                'message' => 'Invalid status.',
                'errors' => ['status' => ['Invalid status value.']],
            ], 422);
        }

        // Don't let admins accidentally lock themselves out
        $ids = collect($validated['ids'])
            ->reject(fn($id) => (int) $id === Auth::id())
            ->values()
            ->all();

        if (empty($ids)) {
            return response()->json([
                'message' => 'You cannot change your own status.',
                'errors' => ['ids' => ['You cannot change your own status.']],
            ], 422);
        }

        $updated = User::whereIn('id', $ids)->update(['status' => $status]);

        return response()->json([
            'status' => $status->value,
            'status_name' => $status->name,
            'label' => $status->label(),
            'updated' => $updated,
            'message' => "Updated {$updated} user(s) to {$status->label()}.",
        ]);
    }

    public function bulkOperation(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:users,id'],
            'operation' => [
                'required',
                'string',
                Rule::in([
                    'activate',
                    'deactivate',
                    'make-employee',
                    'view-employees',
                    'export',
                    'delete',
                    'force-delete',
                ])
            ],
        ]);

        $operation = $validated['operation'];
        $ids = collect($validated['ids'])
            ->reject(fn($id) => (int) $id === Auth::id()
                && in_array($operation, ['delete', 'force-delete', 'deactivate'], true))
            ->values()->all();

        if (empty($ids)) {
            return response()->json([
                'message' => 'No eligible users for this operation.',
                'errors' => ['ids' => ['You cannot perform this operation on your own account.']],
            ], 422);
        }

        DB::beginTransaction();
        try {
            $affected = 0;
            $message = '';

            switch ($operation) {
                case 'activate':
                    $affected = User::whereIn('id', $ids)->update(['status' => ActiveStatus::Active]);
                    $message = "Activated {$affected} user(s).";
                    break;

                case 'deactivate':
                    $affected = User::whereIn('id', $ids)->update(['status' => ActiveStatus::Inactive]);
                    $message = "Deactivated {$affected} user(s).";
                    break;

                case 'make-employee':
                    foreach (User::whereIn('id', $ids)->with('employee')->get() as $user) {
                        if ($user->employee)
                            continue;
                        Employee::create([
                            'user_id' => $user->id,
                            'address' => optional($user->profile)->address,
                            'zip_code' => optional($user->profile)->zip_code,
                            'birthdate' => optional($user->profile)->birthdate,
                            'date_hired' => now(),
                            'status' => EmployeeStatus::Active->value,
                        ]);
                        $affected++;
                    }
                    $message = "Converted {$affected} user(s) to employee(s).";
                    break;

                case 'view-employees':
                    DB::commit();
                    return response()->json([
                        'operation' => 'view-employees',
                        'redirect' => route('users.index', ['role' => 'employee']),
                        'message' => 'Opening employees view…',
                    ]);

                case 'export':
                    DB::commit();
                    return response()->json([
                        'operation' => 'export',
                        'redirect' => route('users.export', [
                            'ids' => implode(',', $ids),
                            'format' => 'csv',
                        ]),
                        'message' => 'Preparing export for ' . count($ids) . ' user(s)…',
                    ]);

                case 'delete':
                    $affected = User::whereIn('id', $ids)->delete();
                    $message = "Deleted {$affected} user(s).";
                    break;

                case 'force-delete':
                    $affected = User::whereIn('id', $ids)->forceDelete();
                    $message = "Permanently removed {$affected} user(s).";
                    break;
            }

            DB::commit();

            return response()->json([
                'operation' => $operation,
                'affected' => $affected,
                'message' => $message,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('[bulk-operation] Failed', [
                'operation' => $operation,
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Operation failed: ' . $e->getMessage(),
                'errors' => ['operation' => ['Server error.']],
            ], 500);
        }
    }

}