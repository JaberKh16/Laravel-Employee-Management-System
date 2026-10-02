<?php

namespace App\Observers;

use App\Models\City;
use Illuminate\Support\Facades\Log;

class CityObserver
{
    public function created(City $city): void
    {
        $this->log('created', $city);
    }

    public function updated(City $city): void
    {
        $this->log('updated', $city, [
            'changes' => $city->getChanges(),
            'original' => $city->getOriginal(),
        ]);
    }

    public function deleted(City $city): void
    {
        $this->log('deleted', $city);
    }

    public function restored(City $city): void
    {
        $this->log('restored', $city);
    }

    public function forceDeleted(City $city): void
    {
        $this->log('forceDeleted', $city);
    }

    /**
     * Write a structured audit log entry.
     */
    protected function log(string $event, City $city, array $extra = []): void
    {
        Log::info("City {$event}", array_merge([
            'id' => $city->id,
            'name' => $city->name,
            'state_id' => $city->state_id,
            'actor_id' => auth()->id(),
            'actor_name' => optional(auth()->user())->username ?? 'system',
            'ip' => request()->ip(),
        ], $extra));
    }
}