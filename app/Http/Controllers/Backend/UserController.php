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
use App\Models\State;
use App\Models\User;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Database\QueryException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
            $roles = Role::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get();


            return view('admin.pages.Users.index', compact(
                'users',
                'downloadOptions',
                'columns',
                'actionButtons',
                'statusOptions',
                'roles'
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

            return view('admin.pages.Users.create', compact('roles', 'statusOptions'));

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
                'hired_date' => now(),
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
                'hired_date' => $user->employee->hired_date,
                'status' => $user->employee->status?->value,
            ] : null,
        ]);
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