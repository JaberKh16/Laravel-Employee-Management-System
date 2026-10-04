<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user): void
    {
        $this->logChange('created', $user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user): void
    {
        $this->logChange('updated', $user);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user): void
    {
        $this->logChange('deleted', $user);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user): void
    {
        $this->logChange('restored', $user);
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user): void
    {
        $this->logChange('force deleted', $user);
    }

    /**
     * Write a structured audit log entry for a user lifecycle event.
     *
     * Safe to call from seeders, queue workers, scheduled tasks, and
     * unauthenticated requests — falls back to "system" when no user
     * is authenticated.
     *
     * @param  string  $action  Human-readable past-tense verb.
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function logChange(string $action, User $user): void
    {
        // Resolve the actor. Prefer username, then email, then "system".
        // Using the null-safe operator (?->) means we never crash when
        // there's no authenticated user (seeders, queues, CLI).
        $actor = auth()->user()?->username
            ?? auth()->user()?->email
            ?? 'system';

        // IMPORTANT: never log the raw $user model. The User model has
        // password and remember_token in $hidden, but (string) casting
        // can bypass $hidden depending on the model's __toString(). Use
        // an explicit allowlist of non-sensitive fields instead.
        Log::info('User ' . $action, [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->status?->value,
            'performed_by' => $actor,
        ]);
    }
}