<?php

namespace Database\Factories;

use App\Http\Enums\BranchStatus;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Branch',
            'code' => strtoupper($this->faker->unique()->bothify('BR-####')),
            'description' => $this->faker->sentence(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->postcode(),
            'manager_name' => $this->faker->name(),
            'status' => $this->faker->randomElement(BranchStatus::values()),
        ];
    }
}