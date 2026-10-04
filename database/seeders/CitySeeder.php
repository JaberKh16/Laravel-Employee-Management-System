<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure states exist first — the FK will reject null state_id.
        if (State::count() === 0) {
            $this->command->warn('No states found — seeding 20 states first.');
            State::factory(20)->create();
        }

        // Pluck IDs once, then reuse the collection. Avoids hitting the
        // DB on every iteration.
        $stateIds = State::pluck('id');

        $seedCount = (int) $this->command->ask(
            'How many cities would you like to create?',
            20
        );

        if ($seedCount < 1) {
            $this->command->warn('Seed count must be at least 1 — skipping.');
            return;
        }

        // Suppress model events during bulk seeding. This prevents
        // audit-log observers from writing one row per city and avoids
        // null-reference crashes if an observer assumes an auth user.
        City::withoutEvents(function () use ($seedCount, $stateIds) {
            City::factory()
                ->count($seedCount)
                ->state(fn() => ['state_id' => $stateIds->random()])
                ->create();
        });

        $this->command->info("Seeded {$seedCount} cities.");
    }
}