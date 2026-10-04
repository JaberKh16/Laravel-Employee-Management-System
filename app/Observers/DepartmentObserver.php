<?php

namespace App\Observers;

use App\Models\Department;
use Illuminate\Support\Facades\Log;

class DepartmentObserver
{

    public function created(Department $department): void
    {
        $this->logChange('created', $department);
    }


    public function updated(Department $department): void
    {
        $this->logChange('updated', $department);
    }


    public function deleted(Department $department): void
    {
        $this->logChange('deleted', $department);
    }


    public function restored(Department $department): void
    {
        $this->logChange('restored', $department);
    }


    public function forceDeleted(Department $department): void
    {
        $this->logChange('force deleted', $department);
    }


    protected function logChange(string $action, Department $department): void
    {
        // Resolve the actor. Prefer username, then email, then a generic
        // "system" label so background jobs don't crash the write.
        $actor = auth()->user()?->username
            ?? auth()->user()?->email
            ?? 'system';

        Log::info('Department ' . $action, [
            'id' => $department->id,
            'name' => $department->name,
            'manager_id' => $department->manager_id,
            'status' => $department->status?->value,
            'performed_by' => $actor,
        ]);
    }
}