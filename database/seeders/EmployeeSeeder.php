<?php

namespace Database\Seeders;

use App\Http\Enums\EmployeeStatus;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\JobProfile;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        /* ══════════════════════════════════════════════════════════
           1. USERS — create if none exist
           ══════════════════════════════════════════════════════════ */
        if (User::count() === 0) {
            $this->command->warn('No users found — creating 50 users first...');
            User::factory()->count(50)->create();
        }

        /* ══════════════════════════════════════════════════════════
           2. DEPARTMENTS — create if none exist
           ══════════════════════════════════════════════════════════ */
        if (Department::count() === 0) {
            $this->command->warn('No departments found — creating 8 departments...');
            Department::factory()->count(8)->create();
        }

        /* ══════════════════════════════════════════════════════════
           3. JOB PROFILES — ensure at least 10
           ══════════════════════════════════════════════════════════ */
        $jobProfileCount = JobProfile::count();
        if ($jobProfileCount < 10) {
            $needed = 10 - $jobProfileCount;
            $this->command->info("Creating {$needed} job profile(s)...");
            JobProfile::factory()->count($needed)->create();
        }

        /* ══════════════════════════════════════════════════════════
           4. LOCATIONS — CRITICAL: must exist for FK integrity
           ══════════════════════════════════════════════════════════ */
        if (Country::count() === 0) {
            $this->command->error(
                'No countries found. Employees have NOT NULL country_id/state_id/city_id. ' .
                'Seed countries/states/cities FIRST (e.g. via LocationSeeder).'
            );
            return;   // ← abort cleanly instead of throwing
        }

        if (State::count() === 0) {
            $this->command->error(
                'No states found. Run your StateSeeder (or similar) before EmployeeSeeder.'
            );
            return;
        }

        if (City::count() === 0) {
            $this->command->error(
                'No cities found. Run your CitySeeder (or similar) before EmployeeSeeder.'
            );
            return;
        }

        /* ══════════════════════════════════════════════════════════
           5. CREATE EMPLOYEES
           ══════════════════════════════════════════════════════════ */
        $total = 50;
        $this->command->info("Seeding {$total} employees...");

        $users = User::inRandomOrder()->take($total)->get();
        $jobProfiles = JobProfile::inRandomOrder()->take($total)->get();
        $departments = Department::pluck('id')->all();
        $countries = Country::pluck('id')->all();

        $bar = $this->command->getOutput()->createProgressBar($users->count());
        $bar->start();

        $created = 0;
        $skipped = 0;

        foreach ($users as $i => $user) {
            // Skip if this user is already an employee
            if (Employee::where('user_id', $user->id)->exists()) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // ── Resolve a country safely ────────────────────────
            $countryId = $countries[array_rand($countries)];

            // ── Resolve a state for that country ────────────────
            $stateId = State::where('country_id', $countryId)
                ->inRandomOrder()
                ->value('id');

            // If no state exists for this country, skip (shouldn't happen
            // if your location seeders are consistent)
            if (!$stateId) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // ── Resolve a city for that state ───────────────────
            $cityId = City::where('state_id', $stateId)
                ->inRandomOrder()
                ->value('id');

            if (!$cityId) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // ── Create via factory (safer than manual create) ────
            try {
                Employee::factory()->create([
                    'user_id' => $user->id,
                    'job_profile_id' => $jobProfiles->get($i)?->id
                        ?? $jobProfiles->first()?->id,
                    'department_id' => $departments
                        ? $departments[array_rand($departments)]
                        : null,
                    'country_id' => $countryId,
                    'state_id' => $stateId,
                    'city_id' => $cityId,
                    'status' => $this->randomStatus(),
                ]);

                $created++;
            } catch (\Throwable $e) {
                // Log and continue — don't abort the whole seeder
                Log::warning('EmployeeSeeder: failed to create employee', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                $this->command->warn(
                    "Skipped user #{$user->id}: {$e->getMessage()}"
                );
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine(2);
        $this->command->info(
            "✅ EmployeeSeeder done. Created: {$created}, Skipped: {$skipped}, " .
            "Total employees: " . Employee::count()
        );
    }

    /**
     * Bias the random status toward Active.
     */
    protected function randomStatus(): EmployeeStatus
    {
        $pool = [
            EmployeeStatus::Active,
            EmployeeStatus::Active,
            EmployeeStatus::Active,
            EmployeeStatus::OnLeave,
            EmployeeStatus::Terminated,
        ];

        return $pool[array_rand($pool)];
    }
}