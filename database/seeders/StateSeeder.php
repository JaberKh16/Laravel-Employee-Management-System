<?php

// namespace Database\Seeders;

// use App\Models\Country;
// use App\Models\State;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Throwable;

// class StateSeeder extends Seeder
// {
//     public function run(): void
//     {
//         try {
//             // Ensure there are countries to attach states to.
//             if (Country::count() === 0) {
//                 $this->command?->warn('No countries found — seeding 20 first.');
//                 Country::factory(20)->create();
//             }

//             // Resolve count from env var (COUNT) or default.
//             // We avoid ask() because it hangs in CI/Docker and
//             // silently returns the default in --no-interaction mode.
//             $seedCount = (int) env('STATE_COUNT', 200);
//             $seedCount = max(0, $seedCount);

//             if ($seedCount === 0) {
//                 $this->command?->warn('StateSeeder skipped: count is 0.');
//                 return;
//             }

//             $countryIds = Country::pluck('id');

//             DB::transaction(function () use ($seedCount, $countryIds) {
//                 // Suppress model events during seeding — no audit logs,
//                 // no auth() crash from an observer that expects a user.
//                 State::withoutEvents(function () use ($seedCount, $countryIds) {
//                     State::factory()
//                         ->count($seedCount)
//                         ->state(fn() => [
//                             'country_id' => $countryIds->random(),
//                         ])
//                         ->create();
//                 });
//             });

//             $this->command?->info("Seeded {$seedCount} states across {$countryIds->count()} countries.");

//         } catch (Throwable $e) {
//             DB::rollBack();

//             Log::error('StateSeeder failed', [
//                 'error' => $e->getMessage(),
//                 'trace' => $e->getTraceAsString(),
//             ]);

//             $this->command?->error('StateSeeder failed: ' . $e->getMessage());
//         }
//     }
// }



namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure countries exist first — the FK will reject null country_id.
        if (Country::count() === 0) {
            $this->command->warn('No countries found — seeding 20 countries first.');
            Country::factory(20)->create();
        }

        $countryIds = Country::pluck('id');

        $seedCount = (int) $this->command->ask(
            'How many states would you like to create?',
            50
        );

        if ($seedCount < 1) {
            $this->command->warn('Seed count must be at least 1 — skipping.');
            return;
        }

        State::withoutEvents(function () use ($seedCount, $countryIds) {
            State::factory()
                ->count($seedCount)
                ->state(fn() => ['country_id' => $countryIds->random()])
                ->create();
        });

        $this->command->info("Seeded {$seedCount} states.");
    }
}