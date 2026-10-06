<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\StateResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class EmployeeController extends Controller
{
    
    // public function index()
    // {
    //     return EmployeeResource::collection(Employee::latest('id')->with(['department', 'country', 'city', 'state'])->paginate(50));
    // }

    
    // public function store(EmployeeStoreRequest $request)
    // {
    //     //dd($request->all());
    //     $employee = Employee::create($request->validated());
    //     return new EmployeeResource($employee);
    // }

    
    // public function show(Employee $employee)
    // {
    //     return new EmployeeResource($employee);
    // }

   
    // public function update(EmployeeUpdateRequest $request, Employee $employee)
    // {
    //     $employee->update($request->validated());
    //     return new EmployeeResource($employee);
    // }

   

    // public function destroy(Employee $employee)
    // {
    //     $employee->delete();
    //     return response()->noContent();
    // }


    public function index(Request $request)
    {
        try {
            $employees = $this->buildFilteredQuery($request)
                ->with([
                    'user.profile',
                    'jobProfile.department',
                    'jobProfile.branch',
                    'country', 'state', 'city',
                ])
                ->paginate((int) $request->get('per_page', 15))
                ->withQueryString();

            return EmployeeResource::collection($employees);

        } catch (Throwable $e) {
            Log::error('Employee index failed', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return response()->json(['message' => 'Unable to load employees.'], 500);
        }
    }

    // ═══════════════════════════════════════════════════════════
    // SHOW
    // ═══════════════════════════════════════════════════════════
    public function show(Employee $employee)
    {
        $employee->load([
            'user.profile',
            'jobProfile.department',
            'jobProfile.branch',
            'country', 'state', 'city',
        ]);

        return new EmployeeResource($employee);
    }

    // ═══════════════════════════════════════════════════════════
    // STORE — create employee + job profile in one transaction
    // ═══════════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Employee core
            'user_id'       => ['required', 'exists:users,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'country_id'    => ['nullable', 'exists:countries,id'],
            'state_id'      => ['nullable', 'exists:states,id'],
            'city_id'       => ['nullable', 'exists:cities,id'],
            'address'       => ['nullable', 'string', 'max:500'],
            'zip_code'      => ['nullable', 'string', 'max:20'],
            'birthdate'     => ['nullable', 'date', 'before:today'],
            'date_hired'    => ['nullable', 'date'],
            'status'        => ['nullable', Rule::in(EmployeeStatus::values())],

            // Job profile
            'designation'             => ['nullable', 'string', 'max:150'],
            'employment_type'         => ['nullable', Rule::in([
                'full_time', 'part_time', 'contract', 'intern', 'temporary', 'freelance',
            ])],
            'basic_salary'            => ['nullable', 'numeric', 'min:0'],
            'allowance'               => ['nullable', 'numeric', 'min:0'],
            'currency'                => ['nullable', 'string', 'size:3'],
            'bank_account'            => ['nullable', 'string', 'max:100'],
            'national_id'             => ['nullable', 'string', 'max:50'],
            'passport_number'         => ['nullable', 'string', 'max:50'],
            'nationality'             => ['nullable', 'string', 'max:100'],
            'marital_status'          => ['nullable', 'string', 'max:30'],
            'phone'                   => ['nullable', 'string', 'max:30'],
            'emergency_contact_name'  => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'postal_code'             => ['nullable', 'string', 'max:20'],
            'branch_id'               => ['nullable', 'exists:branch,id'],
            'notes'                   => ['nullable', 'string'],
            'meta'                    => ['nullable', 'array'],
        ]);

        DB::beginTransaction();

        try {
            // 1. Create the job profile first
            $jobProfile = \App\Models\JobProfile::create([
                'department_id'           => $validated['department_id']           ?? null,
                'designation'             => $validated['designation']             ?? null,
                'employment_type'         => $validated['employment_type']         ?? null,
                'basic_salary'            => $validated['basic_salary']            ?? null,
                'allowance'               => $validated['allowance']               ?? null,
                'currency'                => $validated['currency']                ?? 'USD',
                'bank_account'            => $validated['bank_account']            ?? null,
                'national_id'             => $validated['national_id']             ?? null,
                'passport_number'         => $validated['passport_number']         ?? null,
                'nationality'             => $validated['nationality']             ?? null,
                'marital_status'          => $validated['marital_status']          ?? null,
                'phone'                   => $validated['phone']                   ?? null,
                'emergency_contact_name'  => $validated['emergency_contact_name']  ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'address'                 => $validated['address']                 ?? null,
                'postal_code'             => $validated['postal_code']             ?? null,
                'user_id'                 => $validated['user_id'],
                'branch_id'               => $validated['branch_id']               ?? null,
                'notes'                   => $validated['notes']                   ?? null,
                'meta'                    => $validated['meta']                    ?? null,
            ]);

            // 2. Create the employee linked to it
            $employee = Employee::create([
                'user_id'        => $validated['user_id'],
                'job_profile_id' => $jobProfile->id,
                'department_id'  => $validated['department_id'] ?? null,
                'country_id'     => $validated['country_id']    ?? null,
                'state_id'       => $validated['state_id']      ?? null,
                'city_id'        => $validated['city_id']       ?? null,
                'address'        => $validated['address']       ?? null,
                'zip_code'       => $validated['zip_code']      ?? null,
                'birthdate'      => $validated['birthdate']     ?? null,
                'date_hired'     => $validated['date_hired']    ?? null,
                'status'         => $validated['status']        ?? EmployeeStatus::Active->value,
            ]);

            DB::commit();

            $employee->load(['user.profile', 'jobProfile.department']);

            return (new EmployeeResource($employee))
                ->response()
                ->setStatusCode(201);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Employee store failed', [
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'payload' => $validated,
            ]);

            return response()->json(['message' => 'Unable to create employee.'], 500);
        }
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE
    // ═══════════════════════════════════════════════════════════
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'country_id'    => ['nullable', 'exists:countries,id'],
            'state_id'      => ['nullable', 'exists:states,id'],
            'city_id'       => ['nullable', 'exists:cities,id'],
            'address'       => ['nullable', 'string', 'max:500'],
            'zip_code'      => ['nullable', 'string', 'max:20'],
            'birthdate'     => ['nullable', 'date', 'before:today'],
            'date_hired'    => ['nullable', 'date'],
            'status'        => ['nullable', Rule::in(EmployeeStatus::values())],

            'designation'             => ['nullable', 'string', 'max:150'],
            'employment_type'         => ['nullable', Rule::in([
                'full_time', 'part_time', 'contract', 'intern', 'temporary', 'freelance',
            ])],
            'basic_salary'            => ['nullable', 'numeric', 'min:0'],
            'allowance'               => ['nullable', 'numeric', 'min:0'],
            'currency'                => ['nullable', 'string', 'size:3'],
            'bank_account'            => ['nullable', 'string', 'max:100'],
            'national_id'             => ['nullable', 'string', 'max:50'],
            'passport_number'         => ['nullable', 'string', 'max:50'],
            'nationality'             => ['nullable', 'string', 'max:100'],
            'marital_status'          => ['nullable', 'string', 'max:30'],
            'phone'                   => ['nullable', 'string', 'max:30'],
            'emergency_contact_name'  => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'postal_code'             => ['nullable', 'string', 'max:20'],
            'branch_id'               => ['nullable', 'exists:branch,id'],
            'notes'                   => ['nullable', 'string'],
            'meta'                    => ['nullable', 'array'],
        ]);

        DB::beginTransaction();

        try {
            // Update employee fields
            $employee->update(collect($validated)->only([
                'department_id', 'country_id', 'state_id', 'city_id',
                'address', 'zip_code', 'birthdate', 'date_hired', 'status',
            ])->toArray());

            // Update the linked job profile
            if ($employee->jobProfile) {
                $employee->jobProfile->update(collect($validated)->only([
                    'designation', 'employment_type', 'basic_salary', 'allowance',
                    'currency', 'bank_account', 'national_id', 'passport_number',
                    'nationality', 'marital_status', 'phone',
                    'emergency_contact_name', 'emergency_contact_phone',
                    'address', 'postal_code', 'department_id', 'branch_id',
                    'notes', 'meta',
                ])->toArray());
            }

            DB::commit();

            $employee->refresh()->load(['user.profile', 'jobProfile.department']);

            return new EmployeeResource($employee);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Employee update failed', [
                'error'       => $e->getMessage(),
                'employee_id' => $employee->id,
            ]);

            return response()->json(['message' => 'Unable to update employee.'], 500);
        }
    }

    // ═══════════════════════════════════════════════════════════
    // DESTROY
    // ═══════════════════════════════════════════════════════════
    public function destroy(Employee $employee)
    {
        DB::beginTransaction();

        try {
            $jobProfile = $employee->jobProfile;

            $employee->delete();
            $jobProfile?->delete();

            DB::commit();

            return response()->json(['message' => 'Employee deleted successfully.']);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Employee delete failed', [
                'error' => $e->getMessage(),
                'id'    => $employee->id,
            ]);

            return response()->json(['message' => 'Unable to delete employee.'], 500);
        }
    }

    // ═══════════════════════════════════════════════════════════
    // FILTER META — options for the filter panel
    // ═══════════════════════════════════════════════════════════
    public function filterMeta()
    {
        try {
            return response()->json([
                'departments' => Department::orderBy('name')->get(['id', 'name']),
                'employment_types' => [
                    ['value' => 'full_time', 'label' => 'Full Time'],
                    ['value' => 'part_time', 'label' => 'Part Time'],
                    ['value' => 'contract',  'label' => 'Contract'],
                    ['value' => 'intern',    'label' => 'Intern'],
                    ['value' => 'temporary', 'label' => 'Temporary'],
                    ['value' => 'freelance', 'label' => 'Freelance'],
                ],
                'statuses' => array_map(
                    fn ($s) => ['value' => $s->value, 'label' => $s->label()],
                    EmployeeStatus::cases()
                ),
            ]);
        } catch (Throwable $e) {
            Log::error('Filter meta failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Unable to load filter metadata.'], 500);
        }
    }

    // ═══════════════════════════════════════════════════════════
    // EXPORT — CSV / XLSX / PDF / JSON
    // ═══════════════════════════════════════════════════════════
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');

            $query = $this->buildFilteredQuery($request)
                ->with(['user.profile', 'jobProfile.department', 'jobProfile.branch']);

            $filename = 'employees-' . now()->format('Y-m-d_His');

            return match ($format) {
                'csv', 'xlsx' => Excel::download(new EmployeesExport($query), "{$filename}.{$format}"),
                'pdf'  => Pdf::loadView('exports.employees', [
                            'employees' => (clone $query)->get(),
                         ])->download("{$filename}.pdf"),
                'json' => response()->json((clone $query)->get()),
                default => response()->json(['message' => 'Invalid export format.'], 422),
            };

        } catch (Throwable $e) {
            Log::error('Employee export failed', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ]);

            return response()->json(['message' => 'Unable to export employees.'], 500);
        }
    }

    // ═══════════════════════════════════════════════════════════
    // SHARED FILTER BUILDER (used by index + export)
    // ═══════════════════════════════════════════════════════════
    protected function buildFilteredQuery(Request $request)
    {
        $query = Employee::query();

        // ── Global search
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('address', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($u) use ($s) {
                      $u->where('email', 'like', "%{$s}%")
                        ->orWhere('name', 'like', "%{$s}%")
                        ->orWhereHas('profile', function ($p) use ($s) {
                            $p->where('first_name', 'like', "%{$s}%")
                              ->orWhere('last_name', 'like', "%{$s}%");
                        });
                  })
                  ->orWhereHas('jobProfile', function ($jp) use ($s) {
                      $jp->where('designation', 'like', "%{$s}%")
                         ->orWhere('phone', 'like', "%{$s}%");
                  });
            });
        }

        // ── Advanced filters
        if ($request->filled('designation')) {
            $query->whereHas('jobProfile', fn ($q) =>
                $q->where('designation', 'like', '%' . trim($request->designation) . '%')
            );
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', (int) $request->department_id);
        }

        if ($request->filled('employment_type')) {
            $query->whereHas('jobProfile', fn ($q) =>
                $q->where('employment_type', $request->employment_type)
            );
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('is_promoted')) {
            $query->whereHas('jobProfile', fn ($q) =>
                $q->where('is_promoted', filter_var($request->is_promoted, FILTER_VALIDATE_BOOLEAN))
            );
        }

        if ($request->filled('hired_from')) {
            $query->whereDate('date_hired', '>=', $request->hired_from);
        }
        if ($request->filled('hired_to')) {
            $query->whereDate('date_hired', '<=', $request->hired_to);
        }

        if ($request->filled('salary_min')) {
            $query->whereHas('jobProfile', fn ($q) =>
                $q->where('basic_salary', '>=', (float) $request->salary_min)
            );
        }
        if ($request->filled('salary_max')) {
            $query->whereHas('jobProfile', fn ($q) =>
                $q->where('basic_salary', '<=', (float) $request->salary_max)
            );
        }

        // ── Sorting (employee columns only; jobProfile sort needs a join)
        $sortable  = ['date_hired', 'created_at', 'updated_at', 'id', 'status'];
        $sort      = in_array($request->sort, $sortable, true) ? $request->sort : 'id';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction);
    }

    public function getCountries()
    {
        return CountryResource::collection(Country::latest('id')->get());
    }

    public function getCities()
    {
        return CityResource::collection(City::latest('id')->get());
    }

    public function getStates()
    {
        return StateResource::collection(State::latest('id')->get());
    }

    public function getDepartments()
    {
        return DepartmentResource::collection(Department::latest('id')->get());
    }
}
