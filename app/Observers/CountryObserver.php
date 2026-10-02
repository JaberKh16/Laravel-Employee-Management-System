<?php

namespace App\Observers;

use App\Models\Country;
use Illuminate\Support\Facades\Log;

class CountryObserver
{
    public function created(Country $country): void
    {
        $this->log($country, 'created');
    }

    public function updated(Country $country): void
    {
        $this->log($country, 'updated');
    }

    public function deleted(Country $country): void
    {
        $this->log($country, 'deleted');
    }

    public function restored(Country $country): void
    {
        $this->log($country, 'restored');
    }

    public function forceDeleted(Country $country): void
    {
        $this->log($country, 'force-deleted');
    }

    /**
     * Centralised log writer — safe for CLI / unauthenticated contexts.
     */
    protected function log(Country $country, string $action): void
    {
        $actor = auth()->user()?->username ?? 'system';

        Log::info('Country ' . $action, [
            'id'         => $country->id,
            'name'       => $country->name,
            'actor'      => $actor,
            'ip'         => request()->ip(),
            'timestamp'  => now()->toDateTimeString(),
        ]);
    }
}