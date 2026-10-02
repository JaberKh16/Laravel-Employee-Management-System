<?php

namespace App\Observers;

use App\Models\State;
use Illuminate\Support\Facades\Log;

class StateObserver
{
    public function created(State $state): void
    {
        $this->log('created', $state);
    }

    public function updated(State $state): void
    {
        $this->log('updated', $state, [
            'changes' => $state->getChanges(),
            'original' => $state->getOriginal(),
        ]);
    }

    public function deleted(State $state): void
    {
        $this->log('deleted', $state);
    }

    public function restored(State $state): void
    {
        $this->log('restored', $state);
    }

    public function forceDeleted(State $state): void
    {
        $this->log('forceDeleted', $state);
    }

    protected function log(string $event, State $state, array $extra = []): void
    {
        Log::info("State {$event}", array_merge([
            'id' => $state->id,
            'name' => $state->name,
            'state_code' => $state->state_code,
            'country_id' => $state->country_id,
            'actor_id' => auth()->id(),
            'actor_name' => optional(auth()->user())->username ?? 'system',
            'ip' => request()->ip(),
        ], $extra));
    }
}