<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run()
    // {
    //     //Interactive database seeding
    //     $seedCount = (int) $this->command->ask('How many "department" seeds would you like to create?', 20);
    //     Department::factory($seedCount)->create();
    // }

    public function run(): void
    {
        // ============================================================
        // 1. Real, intentional departments (deterministic)
        // ============================================================
        $departments = [
            [
                'name' => 'Human Resources',
                'description' => 'Manages recruitment, employee relations, benefits, and company culture.',
                'floor' => '2nd Floor',
            ],
            [
                'name' => 'Engineering',
                'description' => 'Builds and maintains the product, platform, and infrastructure.',
                'floor' => '3rd Floor',
            ],
            [
                'name' => 'Sales',
                'description' => 'Drives revenue through customer acquisition, retention, and partnerships.',
                'floor' => '1st Floor',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Owns brand, campaigns, content, and demand generation.',
                'floor' => '1st Floor',
            ],
            [
                'name' => 'Finance',
                'description' => 'Handles accounting, payroll, budgeting, and financial reporting.',
                'floor' => '4th Floor',
            ],
            [
                'name' => 'Customer Support',
                'description' => 'Provides 24/7 support to customers across all channels.',
                'floor' => '2nd Floor',
            ],
        ];

        foreach ($departments as $data) {
            Department::firstOrCreate(
                ['name' => $data['name']],
                [
                    'description' => $data['description'],
                    'floor' => $data['floor'],
                    // manager_id left null; assign below
                ]
            );
        }

        // ============================================================
        // 2. Assign managers from existing users (if any)
        // ============================================================
        $availableUsers = User::query()
            ->whereDoesntHave('managedDepartments')   // only users not already managing
            ->inRandomOrder()
            ->take(Department::whereNull('manager_id')->count())
            ->get();

        Department::whereNull('manager_id')
            ->get()
            ->each(function (Department $department, int $i) use ($availableUsers) {
                if ($user = $availableUsers->get($i)) {
                    $department->update(['manager_id' => $user->id]);
                }
            });

        // ============================================================
        // 3. Optional — extra random departments (local only)
        // ============================================================
        if (app()->environment('local') && Department::count() < 15) {
            $extra = 5;

            $this->command->info("Creating {$extra} random departments…");

            Department::factory()
                ->count($extra)
                ->create();
        }

        // ============================================================
        // 4. Safety net — any dept without a manager gets one
        // ============================================================
        if (User::exists()) {
            Department::whereNull('manager_id')->each(function (Department $department) {
                $user = User::inRandomOrder()->first();
                if ($user) {
                    $department->update(['manager_id' => $user->id]);
                }
            });
        }
    }
}
