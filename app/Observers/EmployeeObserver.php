<?php

namespace App\Observers;

use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class EmployeeObserver
{
    /**
     * Resolve the acting user once, null-safe.
     * Works in HTTP requests, console commands, queues, and seeders.
     */
    protected function actor(): array
    {
        $user = auth()->user();

        return [
            'by_id' => $user?->id,
            'by_username' => $user?->username ?? 'system',
        ];
    }

    /**
     * Common context shared by all events.
     */
    protected function context(Employee $employee, array $extra = []): array
    {
        return array_merge([
            'employee_id' => $employee->id,
            'user_id' => $employee->user_id,
            'department_id' => $employee->department_id,
            'status' => $employee->status?->value ?? $employee->status,
        ], $this->actor(), $extra);
    }

    /* ═══════════════════════════════════════════════════════════
       CREATED
       ═══════════════════════════════════════════════════════════ */
    // public function created(Employee $employee): void
    // {
    //     Log::channel('employee')->info('Employee created', $this->context($employee, [
    //         'attributes' => $employee->getAttributes(),
    //     ]));
    // }

    public function created(Employee $employee): void
    {
        $employee->logActivity('created');
    }



    /* ═══════════════════════════════════════════════════════════
       UPDATED
       ═══════════════════════════════════════════════════════════ */
    // public function updated(Employee $employee): void
    // {
    //     Log::channel('employee')->info('Employee updated', $this->context($employee, [
    //         'changes' => $employee->getChanges(),   // only dirty fields
    //         'original' => collect($employee->getOriginal())
    //             ->only(array_keys($employee->getChanges()))
    //             ->all(),
    //     ]));
    // }
    public function updated(Employee $employee): void
    {
        $employee->logActivity('updated', 'info', [
            'changes' => $employee->getChanges(),
            'original' => collect($employee->getOriginal())
                ->only(array_keys($employee->getChanges()))
                ->all(),
        ]);
    }

    /* ═══════════════════════════════════════════════════════════
       DELETED (soft delete)
       ═══════════════════════════════════════════════════════════ */
    public function deleted(Employee $employee): void
    {
        Log::channel('employee')->warning('Employee deleted', $this->context($employee));
    }

    /* ═══════════════════════════════════════════════════════════
       RESTORED
       ═══════════════════════════════════════════════════════════ */
    public function restored(Employee $employee): void
    {
        Log::channel('employee')->notice('Employee restored', $this->context($employee));
    }

    /* ═══════════════════════════════════════════════════════════
       FORCE DELETED (hard delete from DB)
       ═══════════════════════════════════════════════════════════ */
    public function forceDeleted(Employee $employee): void
    {
        Log::channel('employee')->critical('Employee force-deleted', $this->context($employee));
    }
}