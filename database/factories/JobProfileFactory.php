<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Department;
use App\Models\JobProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobProfileFactory extends Factory
{
    protected $model = JobProfile::class;

    public function definition(): array
    {
        $user = User::query()->inRandomOrder()->first()
            ?? User::factory()->create();
        $department = Department::query()->inRandomOrder()->first();
        $branch = Branch::query()->inRandomOrder()->first();

        $designations = [
            'Software Engineer',
            'Senior Software Engineer',
            'Product Manager',
            'QA Engineer',
            'HR Specialist',
            'Accountant',
            'Sales Executive',
            'Marketing Lead',
            'DevOps Engineer',
            'Data Analyst',
            'Customer Support',
            'Operations Manager',
        ];

        $employmentTypes = ['full_time', 'part_time', 'contract', 'intern'];
        $currencies = ['USD', 'EUR', 'GBP', 'BDT', 'INR'];
        $maritalStatuses = ['single', 'married', 'divorced', 'widowed'];

        return [
            'user_id' => $user->id,
            'department_id' => $department?->id,
            'branch_id' => $branch?->id,
            'employment_type' => $this->faker->randomElement($employmentTypes),
            'designation' => $this->faker->randomElement($designations),
            'calculated_years' => $this->faker->numberBetween(0, 25),
            'is_promoted' => $this->faker->boolean(25), // 25% promoted
            'last_promotion_date' => $this->faker->optional(0.4)
                ->dateTimeBetween('-5 years', 'now'),
            'basic_salary' => $this->faker->randomFloat(2, 1500, 25000),
            'allowance' => $this->faker->randomFloat(2, 0, 3000),
            'currency' => $this->faker->randomElement($currencies),
            'bank_account' => $this->faker->bankAccountNumber(),
            'national_id' => strtoupper($this->faker->bothify('??########')),
            'passport_number' => strtoupper($this->faker->bothify('??#######')),
            'nationality' => $this->faker->country(),
            'marital_status' => $this->faker->randomElement($maritalStatuses),
            'phone' => $this->faker->phoneNumber(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'notes' => $this->faker->optional(0.5)->sentence(),
            'meta' => [
                'skills' => $this->faker->randomElements(
                    ['PHP', 'Laravel', 'Vue', 'React', 'MySQL', 'Postgres', 'Docker', 'AWS'],
                    $this->faker->numberBetween(2, 5)
                ),
                'remote' => $this->faker->boolean(),
                'referred' => $this->faker->boolean(15),
            ],
        ];
    }
}