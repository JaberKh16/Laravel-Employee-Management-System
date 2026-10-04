<?php

namespace Database\Factories;

use App\Http\Enums\ActiveStatus;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    /**
     * Real-ish department names to avoid "quo" nonsense.
     */
    protected static array $names = [
        'Human Resources',
        'Engineering',
        'Sales',
        'Marketing',
        'Finance',
        'Operations',
        'Customer Support',
        'Research & Development',
        'Legal',
        'IT Infrastructure',
        'Product',
        'Design',
        'Procurement',
        'Quality Assurance',
        'Logistics',
    ];

    /**
     * Floors to keep them realistic.
     */
    protected static array $floors = [
        '1st Floor',
        '2nd Floor',
        '3rd Floor',
        '4th Floor',
        '5th Floor',
        'Basement',
        'Ground Floor',
        'Mezzanine',
    ];

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(self::$names),
            'description' => $this->faker->paragraph(2),
            'floor' => $this->faker->randomElement(self::$floors),

            // manager_id → nullable, so default to no manager.
            // Use ->withManager() or ->forManager($user) to attach one.
            'manager_id' => null,

            // status → defaults to Active via DB default or the enum.
            // Override with ->inactive() below.
            'status' => ActiveStatus::Active->value,
        ];
    }

    // ============================================================
    // STATES
    // ============================================================

    /**
     * Attach a fresh manager (creates a new User).
     * Usage: Department::factory()->withManager()->create();
     */
    public function withManager(): static
    {
        return $this->state([
            'manager_id' => User::factory(),
        ]);
    }

    /**
     * Attach an existing user as manager.
     * Usage: Department::factory()->forManager($user)->create();
     */
    public function forManager(User $user): static
    {
        return $this->state([
            'manager_id' => $user->id,
        ]);
    }

    /**
     * No manager at all.
     * Usage: Department::factory()->withoutManager()->create();
     */
    public function withoutManager(): static
    {
        return $this->state([
            'manager_id' => null,
        ]);
    }

    /**
     * Minimal department — no description, no floor.
     */
    public function minimal(): static
    {
        return $this->state([
            'description' => null,
            'floor' => null,
            'manager_id' => null,
        ]);
    }

    /**
     * Inactive department (status = 0).
     * Usage: Department::factory()->inactive()->create();
     */
    public function inactive(): static
    {
        return $this->state([
            'status' => ActiveStatus::Inactive->value,
        ]);
    }

    /**
     * Active department (status = 1) — explicit for readability.
     * Usage: Department::factory()->active()->create();
     */
    public function active(): static
    {
        return $this->state([
            'status' => ActiveStatus::Active->value,
        ]);
    }
}