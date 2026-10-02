<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        if (Country::count() === 0) {
            $this->command->warn('No countries found — seeding 5 countries first.');
            Country::factory(5)->create();
        }

        $countryIds = Country::pluck('id');

        $seedCount = (int) $this->command->ask(
            'How many "state" seeds would you like to create?',
            200
        );

        // Suppress the observer during seeding — no audit logs, no auth() crash.
        State::withoutEvents(function () use ($seedCount, $countryIds) {
            State::factory()
                ->count($seedCount)
                ->state(fn() => ['country_id' => $countryIds->random()])
                ->create();
        });
    }
}