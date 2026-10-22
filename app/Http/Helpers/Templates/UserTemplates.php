<?php

namespace App\Http\Helpers\Templates;

use App\Http\Enums\ActiveStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Throwable;
use App\Models\Department;
use App\Models\City;
use App\Models\Country;
use App\Models\Branch;

class UserTemplates
{
    /* ══════════════════════════════════════════════════════════════
       BUILD FILTERED QUERY
       Used by: index, export
       ══════════════════════════════════════════════════════════════ */
    public function buildFilteredQuery(Request $request)
    {
        $query = User::with(['profile', 'roles', 'employee.department']);

        // ── Global search ──────────────────────────────────────────
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($p) use ($search) {
                        $p->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // ── Criteria: Name ─────────────────────────────────────────
        if ($name = $request->input('name')) {
            $query->where(function ($q) use ($name) {
                $q->where('username', 'like', "%{$name}%")
                    ->orWhereHas('profile', function ($p) use ($name) {
                        $p->where('first_name', 'like', "%{$name}%")
                            ->orWhere('last_name', 'like', "%{$name}%");
                    });
            });
        }

        // ── Criteria: Role ─────────────────────────────────────────
        if ($role = $request->input('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $role));
        }

        // ── Criteria: Email ────────────────────────────────────────
        if ($email = $request->input('email')) {
            $query->where('email', 'like', "%{$email}%");
        }

        // ── Criteria: Date (created_at) ────────────────────────────
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // ── Criteria: DOB ──────────────────────────────────────────
        if ($dob = $request->input('dob')) {
            $query->whereHas('profile', fn($p) => $p->whereDate('birthdate', $dob));
        }

        // ── Criteria: Status ───────────────────────────────────────
        if ($status = $request->input('status')) {
            // Accept int-backed or string-backed enum; ignore invalid input.
            $enum = ActiveStatus::tryFrom($status)
                ?? (is_numeric($status) ? ActiveStatus::tryFrom((int) $status) : null);

            if ($enum) {
                $query->where('status', $enum->value);
            }
        }

        // ── Criteria: Timestamp range ──────────────────────────────
        if ($from = $request->input('updated_from')) {
            $query->where('updated_at', '>=', $from);
        }
        if ($to = $request->input('updated_to')) {
            $query->where('updated_at', '<=', $to);
        }

        // ── Sorting ────────────────────────────────────────────────
        $allowedSorts = ['updated_at', 'created_at', 'username', 'email', 'status', 'id'];

        $sort = in_array($request->input('sort'), $allowedSorts, true)
            ? $request->input('sort')
            : 'updated_at';

        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction);
    }

    /* ══════════════════════════════════════════════════════════════
       COLUMNS (for the table header)
       ══════════════════════════════════════════════════════════════ */
    public function columnsToSelect(): array
    {
        return [
            'index' => '#',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'status' => 'Status',
            'role' => 'Role',
            'updated_at' => 'Last Modified',
            'actions' => 'Actions',
            // 'operations' => 'Bulk',
        ];
    }

    /* ══════════════════════════════════════════════════════════════
       COLUMNS (for export — hides #/actions)
       ══════════════════════════════════════════════════════════════ */
    public function columnsToExport(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'status' => 'Status',
            'role' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Last Modified',
        ];
    }

    /* ══════════════════════════════════════════════════════════════
       Filter Criterias 
       ══════════════════════════════════════════════════════════════ */
    public function filterCriterias(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'date' => 'Date',
            'dob' => 'DOB',
            'status' => 'Status',
            'role' => 'Role',
            'timestamp' => 'Timestamp',
            'sort' => 'Sort'
        ];
    }

    /* ══════════════════════════════════════════════════════════════
       DOWNLOAD OPTIONS (for dropdown)
       ══════════════════════════════════════════════════════════════ */
    public function downloadOptionsForExport(): array
    {
        return [
            ['format' => 'csv', 'label' => 'CSV', 'icon' => 'ri-file-excel-2-line', 'color' => 'text-emerald-600'],
            ['format' => 'xlsx', 'label' => 'Excel (XLSX)', 'icon' => 'ri-file-excel-2-line', 'color' => 'text-green-600'],
            ['format' => 'pdf', 'label' => 'PDF', 'icon' => 'ri-file-pdf-2-line', 'color' => 'text-red-600'],
            ['format' => 'json', 'label' => 'JSON', 'icon' => 'ri-file-code-line', 'color' => 'text-amber-600'],
            ['format' => 'print', 'label' => 'Print', 'icon' => 'ri-printer-line', 'color' => 'text-blue-600'],
        ];
    }

    

    public function actionButtons(User $user): array
    {
        // Build routes safely — if any route name is missing, we skip it
        $viewProfileRoute = $this->safeRoute('users.profile.modal.show', $user->id);
        $makeEmployeeRoute = $this->safeRoute('users.make-employee', $user->id);
        $editRoute = $this->safeRoute('users.edit', $user->id);
        $deleteRoute = $this->safeRoute('users.destroy', $user->id);

        return [
            'view_profile' => $viewProfileRoute ? [
                'route' => $viewProfileRoute,
                'icon' => 'ri-eye-line',
                'label' => 'View Profile',
                'color' => 'text-slate-600',
            ] : null,

            'make_employee' => $makeEmployeeRoute ? [
                'route' => $makeEmployeeRoute,
                'icon' => 'ri-briefcase-4-line',
                'label' => 'Make Employee',
                'color' => 'text-emerald-600',
                'confirm' => true,
            ] : null,

            'edit' => $editRoute ? [
                'route' => $editRoute,
                'icon' => 'ri-edit-line',
                'label' => 'Edit',
                'color' => 'text-indigo-600',
            ] : null,

            'delete' => $deleteRoute ? [
                'route' => $deleteRoute,
                'icon' => 'ri-delete-bin-6-line',
                'label' => 'Delete',
                'color' => 'text-red-600',
                'confirm' => true,
            ] : null,
        ];
    }

    /**
     * Safely build a route URL, returning null if the route doesn't exist.
     */
    private function safeRoute(string $name, mixed $params = null): ?string
    {
        try {
            return Route::has($name)
                ? route($name, $params)
                : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function configureEmployeeForm(User $user, array $options = [], ?string $action = null): array
    {
        $user->load([
            'profile.country',
            'profile.state',
            'profile.city',
            'employee',
            'employee.jobProfile',
            'employee.department',
            'employee.jobProfile.branch',
        ]);

        $p = $user->profile;
        $employee = $user->employee;  

        // $isEmployee = (bool) $employee;
        $isEmployee = $user->employee()->exists();
        $jp = $employee?->jobProfile;

        return [
            'id'            => $user->id,
            'username'      => $user->username,
            'name'          => $user->name ?? $user->username,
            'email'         => $user->email,
            // 'is_employee'   => $user->employee()->exists(),
            'is_employee'   => $isEmployee,
            'profile'       => [
                'first_name'  => $p?->first_name,
                'middle_name' => $p?->middle_name,
                'last_name'   => $p?->last_name,
                'birthdate'   => $p?->birthdate?->format('Y-m-d'),
                'gender'      => $p?->gender,
                'zip_code'    => $p?->zip_code,
                'address'     => $p?->address,
                'country_id'  => $p?->country_id,
                'state_id'    => $p?->state_id,
                'city_id'     => $p?->city_id,
                'country_name'=> $p?->country?->name,
                'state_name'  => $p?->state?->name,
                'city_name'   => $p?->city?->name,
            ],
            // ── Employee record (present only if is_employee) ──
            'employee' => $employee ? [
                'id'            => $employee->id,
                'department_id' => $employee->department_id,
                'status'        => $employee->status?->value ?? $employee->status,
                'hired_date'    => $employee->hired_date?->format('Y-m-d'),
            ] : null,

            // ── Job profile (null if not generated yet) ──
            'job_profile_id' => $jp?->id,
            'job_profile'    => $jp ? [
                'id'                    => $jp->id,
                'employment_type'       => $jp->employment_type,
                'designation'           => $jp->designation,
                'department_id'         => $jp->department_id,
                'branch_id'             => $jp->branch_id,
                'calculated_years'      => $jp->calculated_years,
                'is_promoted'           => (bool) $jp->is_promoted,
                'last_promotion_date'   => $jp->last_promotion_date?->format('Y-m-d'),
                'basic_salary'          => $jp->basic_salary,
                'allowance'             => $jp->allowance,
                'currency'              => $jp->currency,
                'bank_account'          => $jp->bank_account,
                'national_id'           => $jp->national_id,
                'passport_number'       => $jp->passport_number,
                'nationality'           => $jp->nationality,
                'marital_status'        => $jp->marital_status,
                'phone'                 => $jp->phone,
                'emergency_contact_name'=> $jp->emergency_contact_name,
                'emergency_contact_phone'=> $jp->emergency_contact_phone,
                'address'               => $jp->address,
                'postal_code'           => $jp->postal_code,
                'notes'                 => $jp->notes,
                'meta'                  => $jp->meta,
            ] : null,

            'make_emp_url'  => route('users.make-employee', $user),
            'update_url'    => $user->employee()->exists()
                    ? route('users.update-employee', $user)
                    : null,
            'status_options'=> $this->getStatus(),
            'roles'         => $this->getRoles(),
            'departments'   => $this->getDepartments(),
            'cities'        => $this->getCities(),
            'countries'     => $this->getCountries(),
            'branches'      => $this->getBranches(),
            'options'       => $options,
            'action'        => $action,
        ];
    }

    public function getRoles()
    {
        $roles = Role::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return $roles;
    }

    public function getDepartments()
    {
        $depts = Department::query()->select('id', 'name')->orderBy('id', 'asc')->get();
        return $depts;
    }

    public function getCities()
    {
        $cities = City::query()->select('id', 'name')->orderBy('id', 'asc')->get();
        return $cities;
    }

    public function getCountries()
    {
        $cities = Country::query()->select('id', 'name')->orderBy('id', 'asc')->get();
        return $cities;
    }

    public function getBranches()
    {
        $cities = Branch::query()->select('id', 'name')->orderBy('id', 'asc')->get();
        return $cities;
    }


    public function getStatus()
    {
        // Build a status meta map keyed by enum value.
        $statusOptions = collect(ActiveStatus::cases())->mapWithKeys(function (ActiveStatus $status) {
            return [
                $status->value => [
                    'value' => $status->value,
                    'name' => $status->name,   // Active / Inactive / Pending / Banned
                    'label' => $status->label(),
                    'classes' => match ($status) {
                        ActiveStatus::Active => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
                        ActiveStatus::Inactive => 'border-gray-200 bg-gray-100 text-gray-600 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300',
                        ActiveStatus::Pending => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
                        ActiveStatus::Confirmed => 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/60 dark:text-green-300',
                        ActiveStatus::Banned => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/60 dark:text-red-300',
                    },
                    'dot' => match ($status) {
                        ActiveStatus::Active => 'bg-emerald-500',
                        ActiveStatus::Inactive => 'bg-gray-400',
                        ActiveStatus::Pending => 'bg-amber-500',
                        ActiveStatus::Confirmed => 'bg-green-500',
                        ActiveStatus::Banned => 'bg-red-500',
                    },
                ],
            ];
        })->all();
        return $statusOptions;
    }
}