<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        // Make sure states exist first.
        if (State::count() === 0) {
            $this->command->warn('No states found — seeding 20 states first.');
            State::factory(20)->create();
        }

        // Pluck IDs once — one query instead of N.
        $stateIds = State::pluck('id');

        $seedCount = (int) $this->command->ask(
            'How many "city" seeds would you like to create?',
            20
        );

        // Suppress the observer so we don't write 20 audit-log lines
        // (and don't crash on auth()->user()->username being null).
        City::withoutEvents(function () use ($seedCount, $stateIds) {
            City::factory()
                ->count($seedCount)
                ->state(fn() => ['state_id' => $stateIds->random()])
                ->create();
        });
    }
}