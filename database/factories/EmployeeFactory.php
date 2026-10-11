<?php

namespace Database\Factories;

use App\Http\Enums\EmployeeStatus;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\JobProfile;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        /* ══════════════════════════════════════════════════════════
           USER — required
           ══════════════════════════════════════════════════════════ */
        $user = User::query()->inRandomOrder()->first()
            ?? User::factory()->create();

        /* ══════════════════════════════════════════════════════════
           JOB PROFILE — optional, but only link if one exists
           ══════════════════════════════════════════════════════════ */
        $jobProfile = JobProfile::query()->inRandomOrder()->first();

        /* ══════════════════════════════════════════════════════════
           LOCATIONS — resolve in order: country → state → city
           ══════════════════════════════════════════════════════════ */
        $country = Country::query()->inRandomOrder()->first();

        $state = $country
            ? State::query()
                ->where('country_id', $country->id)
                ->inRandomOrder()
                ->first()
            : null;

        $city = $state
            ? City::query()
                ->where('state_id', $state->id)
                ->inRandomOrder()
                ->first()
            : null;

        /* ══════════════════════════════════════════════════════════
           DEPARTMENT — optional
           ══════════════════════════════════════════════════════════ */
        $department = Department::query()->inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'job_profile_id' => $jobProfile?->id,
            'department_id' => $department?->id,
            'country_id' => $country?->id,
            'state_id' => $state?->id,
            'city_id' => $city?->id,
            'hired_date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'status' => $this->faker->randomElement([
                EmployeeStatus::Active,
                EmployeeStatus::Active,
                EmployeeStatus::Active,
                EmployeeStatus::OnLeave,
                EmployeeStatus::Terminated,
            ]),
        ];
    }

    /* ═══════════════════════════════════════════════════════════
       STATES
       ═══════════════════════════════════════════════════════════ */
    public function active(): static
    {
        return $this->state(fn() => ['status' => EmployeeStatus::Active]);
    }

    public function onLeave(): static
    {
        return $this->state(fn() => ['status' => EmployeeStatus::OnLeave]);
    }

    public function terminated(): static
    {
        return $this->state(fn() => ['status' => EmployeeStatus::Terminated]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn() => ['user_id' => $user->id]);
    }

    public function forDepartment(Department $department): static
    {
        return $this->state(fn() => ['department_id' => $department->id]);
    }

    public function forLocation(Country $country, ?State $state = null, ?City $city = null): static
    {
        return $this->state(fn() => [
            'country_id' => $country->id,
            'state_id' => $state?->id,
            'city_id' => $city?->id,
        ]);
    }
}