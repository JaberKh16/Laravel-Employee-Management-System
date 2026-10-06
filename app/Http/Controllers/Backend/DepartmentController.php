<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentStoreRequest;
use App\Models\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Enums\ActiveStatus;
use Illuminate\Validation\Rule;
use App\Http\Enums\DepartmentStatus;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\DepartmentsExport;

class DepartmentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:department-list|department-create|department-edit|department-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:department-create', ['only' => ['create','store']]);
        // $this->middleware('permission:department-edit',   ['only' => ['edit','update']]);
        // $this->middleware('permission:department-delete', ['only' => ['destroy']]);
    }

    // ═══════════════════════════════════════════════════════════
    // BUILD FILTERED QUERY (shared by index + export)
    // ═══════════════════════════════════════════════════════════
    /**
     * Apply all filters, search, and sorting to the Department query.
     *
     * Returns an Eloquent Builder — NOT a Collection. Callers decide
     * whether to ->paginate(), ->get(), or stream via an export class.
     */
    protected function buildFilteredQuery(Request $request)
    {
        $query = Department::query();

        // ── Global search (name OR description)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // ── Advanced filter: name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . trim($request->name) . '%');
        }

        // ── Advanced filter: floor
        if ($request->filled('floor')) {
            $query->where('floor', 'like', '%' . trim($request->floor) . '%');
        }

        // ── Advanced filter: status (enum backed value)
        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        // ── Advanced filter: created date range
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // ── Sorting (whitelist to prevent SQL injection)
        $sortable  = ['name', 'floor', 'status', 'created_at', 'updated_at', 'id'];
        $sort      = in_array($request->sort, $sortable, true) ? $request->sort : 'id';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction);
    }
 
    public function index(Request $request)
    {
        try {
            $departments = $this->buildFilteredQuery($request)
                ->paginate(15)
                ->withQueryString();

            return view('admin.pages.Department.index', [
                'departments'      => $departments,
                'departmentStatus' => DepartmentStatus::cases(),
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to load departments list', [
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load departments. Please try again.');
        }
    }


   
    public function create()
    {
        return view('admin.pages.Department.create', [
            'departmentStatus' => ActiveStatus::cases(),
        ]);
    }

  
    public function store(DepartmentStoreRequest $request)
    {
        try {
            Department::create($request->validated());

            $this->notifySuccess('Department Created Successfully!!!');

            return redirect()
                ->route('departments.index')
                ->with('success', 'Department Created Successfully!!!');

        } catch (QueryException $e) {
            // 23000 = Integrity constraint violation (duplicate / FK)
            if ($this->isDuplicateEntry($e)) {
                $this->notifyError('A department with that name already exists.');

                return back()
                    ->withInput()
                    ->withErrors(['name' => 'A department with that name already exists.']);
            }

            // Unknown DB error — log it and show a generic message
            Log::error('Department store failed: ' . $e->getMessage(), [
                'exception' => $e,
                'payload'   => $request->validated(),
            ]);

            $this->notifyError('Something went wrong while saving the department. Please try again.');

            return back()
                ->withInput()
                ->withErrors(['name' => 'Could not save the department. Please try again.']);
        }
    }

  
    public function show(Department $department)
    {
        //
    }

    public function edit(Department $department)
    {
        $departmentStatus = ActiveStatus::cases();
        return view('admin.pages.Department.edit', compact('department', 'departmentStatus'));
    }

  
    public function update(DepartmentStoreRequest $request, Department $department)
    {
        try {
            $department->update($request->validated());

            $this->notifySuccess('Department Updated Successfully!!!');

            return redirect()
                ->route('departments.index')
                ->with('success', 'Department Updated Successfully!!!');

        } catch (QueryException $e) {
            if ($this->isDuplicateEntry($e)) {
                $this->notifyError('A department with that name already exists.');

                return back()
                    ->withInput()
                    ->withErrors(['name' => 'A department with that name already exists.']);
            }

            Log::error('Department update failed: ' . $e->getMessage(), [
                'exception'    => $e,
                'department_id'=> $department->id,
                'payload'      => $request->validated(),
            ]);

            $this->notifyError('Something went wrong while updating the department. Please try again.');

            return back()
                ->withInput()
                ->withErrors(['name' => 'Could not update the department. Please try again.']);
        }
    }

 
    public function destroy(Department $department)
    {
        try {
            // Optional safety: prevent deleting a department that has children.
            // if ($department->employees()->exists()) {
            //     $this->notifyError('Cannot delete — department still has employees.');
            //     return redirect()->route('departments.index');
            // }

            $department->delete();

            $this->notifySuccess('Department Deleted Successfully!!!');

            return redirect()
                ->route('departments.index')
                ->with('success', 'Department Deleted Successfully!!!');

        } catch (QueryException $e) {
            // 1451 = Cannot delete or update a parent row (FK constraint)
            if ($this->isForeignKeyConstraint($e)) {
                $this->notifyError('Cannot delete — this department is still referenced by other records.');

                return redirect()
                    ->route('departments.index')
                    ->with('error', 'Cannot delete — this department is still referenced by other records.');
            }

            Log::error('Department delete failed: ' . $e->getMessage(), [
                'exception'     => $e,
                'department_id' => $department->id,
            ]);

            $this->notifyError('Something went wrong while deleting the department. Please try again.');

            return redirect()
                ->route('departments.index')
                ->with('error', 'Could not delete the department. Please try again.');
        }
    }

  
    private function isDuplicateEntry(QueryException $e): bool
    {
        // MySQL error code 1062
        if (($e->errorInfo[1] ?? null) === 1062) {
            return true;
        }

        // SQLite reports "UNIQUE constraint failed"
        // Postgres reports SQLSTATE 23505
        $message = $e->getMessage();

        return str_contains($message, 'Duplicate entry')
            || str_contains($message, 'UNIQUE constraint failed')
            || str_contains($message, 'duplicate key value');
    }

 
    private function isForeignKeyConstraint(QueryException $e): bool
    {
        if (($e->errorInfo[1] ?? null) === 1451) {
            return true;
        }

        $message = $e->getMessage();

        return str_contains($message, 'foreign key constraint')
            || str_contains($message, 'Cannot delete or update a parent row');
    }


    private function notifySuccess(string $message): void
    {
        if (function_exists('notify')) {
            notify()->success($message, 'Success', 'topRight');
        }
    }

    private function notifyError(string $message): void
    {
        if (function_exists('notify')) {
            notify()->error($message, 'Error', 'topRight');
        }
    }


    

    /**
     * Update the status of a single department.
     */
      public function updateStatus(Request $request, Department $department)
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', Rule::in(ActiveStatus::values())],
        ]);

        try {
            $department->update(['status' => $validated['status']]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Department status updated.',
                    'data'    => [
                        'id'     => $department->id,
                        'status' => $department->status?->value,   // int
                        'label'  => $department->status?->label(),
                        'color'  => $department->status?->color(),
                    ],
                ]);
            }

            notify()->success('Department status updated.', 'Success', 'topRight');

            return redirect()
                ->route('departments.index', $request->only('search', 'page'))
                ->with('success', 'Department status updated.');

        } catch (QueryException $e) {
            Log::error('Status update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'id'        => $department->id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not update status.',
                ], 500);
            }

            notify()->error('Could not update status.', 'Error', 'topRight');
            return redirect()->route('departments.index')
                ->with('error', 'Could not update status.');
        }
    }

    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $query  = $this->buildFilteredQuery($request);

            $filename = 'departments-' . now()->format('Y-m-d_His');

            return match ($format) {
                'csv'   => Excel::download(new DepartmentsExport($query), "{$filename}.csv"),
                'xlsx'  => Excel::download(new DepartmentsExport($query), "{$filename}.xlsx"),
                'pdf'   => PDF::loadView('exports.departments', [
                                'departments' => (clone $query)->get(),
                            ])->download("{$filename}.pdf"),
                'json'  => response()->json((clone $query)->get()),
                default => back()->with('error', 'Invalid export format.'),
            };

        } catch (Throwable $e) {
            Log::error('Failed to export departments', [
                'error'  => $e->getMessage(),
                'file'   => $e->getFile(),
                'line'   => $e->getLine(),
                'format' => $request->get('format'),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Unable to export departments.');
        }
    }
}