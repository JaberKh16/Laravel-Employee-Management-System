<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class EmployeeObserver
{
   
    protected function actor(): array
    {
        $user = auth()->user();

        return [
            'by_id' => $user?->id,
            'by_username' => $user?->username ?? 'system',
        ];
    }

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

        // Make sure relations are loaded before reading them
        $employee->loadMissing(['user.profile', 'department']);

        $name       = $employee->user?->profile?->full_name
                    ?? $employee->user?->username
                    ?? 'Employee #' . $employee->id;

        $department = $employee->department?->name ?? 'no department';

        try {
            $admins = User::role('admin')->pluck('id')->all();

            if (! empty($admins)) {
                NotificationService::toUsers(
                    userIds: $admins,
                    type:    'employee.created',
                    title:   'New employee added',
                    message: "{$name} joined {$department}.",
                    options: [
                        'category'     => 'success',
                        'icon'         => 'user-plus',
                        'action_url'   => route('employees.show', $employee),
                        'action_label' => 'View employee',
                        'related_type' => Employee::class,
                        'related_id'   => $employee->id,
                    ]
                );
            }
        } catch (\Throwable $e) {
            // Notification failure should NOT roll back employee creation
            Log::channel('employee')->error('Employee-created notification failed', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
            ]);
        }
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
        $changes = $employee->getChanges();

        // Nothing actually changed? Skip.
        if (empty($changes)) {
            return;
        }

        $original = collect($employee->getOriginal())
            ->only(array_keys($changes))
            ->all();

        $employee->logActivity('updated', 'info', [
            'changes'  => $changes,
            'original' => $original,
        ]);
    }
    
    /* ═══════════════════════════════════════════════════════════
       DELETED (soft delete)
       ═══════════════════════════════════════════════════════════ */
    public function deleted(Employee $employee): void
    {
        Log::channel('employee')->warning('Employee deleted', $this->context($employee));
        $employee->logActivity('deleted', 'warning');
    }

    /* ═══════════════════════════════════════════════════════════
       RESTORED
       ═══════════════════════════════════════════════════════════ */
    public function restored(Employee $employee): void
    {
        Log::channel('employee')->notice('Employee restored', $this->context($employee));
        $employee->logActivity('restored', 'notice');
    }

    /* ═══════════════════════════════════════════════════════════
       FORCE DELETED (hard delete from DB)
       ═══════════════════════════════════════════════════════════ */
    public function forceDeleted(Employee $employee): void
    {
        Log::channel('employee')->critical('Employee force-deleted', $this->context($employee));
        $employee->logActivity('force-deleted', 'critical');
    }
}