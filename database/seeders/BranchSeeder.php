<?php

namespace Database\Seeders;

use App\Http\Enums\BranchStatus;
use App\Models\Branch;
use App\Models\City;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. Deterministic — a few named branches with real locations
        // ============================================================
        $branches = [
            [
                'name' => 'Headquarters',
                'code' => 'BR-0001',
                'email' => 'hq@example.com',
                'phone' => '+1-555-0100',
                'manager_name' => 'Alex Morgan',
                'address' => '100 Main Street',
                'zip_code' => '10001',
            ],
            [
                'name' => 'West Coast Branch',
                'code' => 'BR-0002',
                'email' => 'west@example.com',
                'phone' => '+1-555-0200',
                'manager_name' => 'Jordan Lee',
                'address' => '200 Sunset Blvd',
                'zip_code' => '90001',
            ],
            [
                'name' => 'East Coast Branch',
                'code' => 'BR-0003',
                'email' => 'east@example.com',
                'phone' => '+1-555-0300',
                'manager_name' => 'Casey Patel',
                'address' => '300 Broadway',
                'zip_code' => '10012',
            ],
        ];

        foreach ($branches as $data) {
            Branch::firstOrCreate(
                ['code' => $data['code']],
                array_merge($data, [
                    'status' => BranchStatus::Active->value,
                    'description' => null,
                    'country_id' => null,
                    'state_id' => null,
                    'city_id' => null,
                ])
            );
        }

        // ============================================================
        // 2. Attach locations to any branch that doesn't have one
        // ============================================================
        if (City::exists()) {
            Branch::whereNull('city_id')->each(function (Branch $branch) {
                $city = City::query()->inRandomOrder()->first();
                if (!$city) {
                    return;
                }

                $state = $city->state;

                $branch->update([
                    'city_id' => $city->id,
                    'state_id' => $city->state_id,
                    'country_id' => $state?->country_id,
                ]);
            });
        }

        // ============================================================
        // 3. Random filler branches (local only)
        // ============================================================
        if (app()->environment('local')) {
            $extra = 15;

            $this->command->info("Creating {$extra} random branches…");

            Branch::factory()
                ->count($extra)
                ->when(
                    City::exists(),
                    fn($factory) => $factory->withLocation(),
                    fn($factory) => $factory
                )
                ->create([
                    'status' => BranchStatus::Active->value,
                ]);
        }
    }
}