<?php

namespace Database\Factories;

use App\Http\Enums\BranchStatus;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
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
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->postcode(),
            'manager_name' => $this->faker->name(),
            'status' => BranchStatus::Active->value,

            // Location — default to null; use ->withLocation() or
            // ->forCity($city) to attach a real chain.
            'country_id' => null,
            'state_id' => null,
            'city_id' => null,
        ];
    }

    /* ============================================================
     |  STATES
     ============================================================ */

    /**
     * Attach a real country/state/city chain.
     *
     * Usage: Branch::factory()->withLocation()->create();
     */
    public function withLocation(): static
    {
        return $this->state(function () {
            $city = City::query()->inRandomOrder()->first()
                ?? City::factory()->create();

            $state = $city->state;

            return [
                'city_id' => $city->id,
                'state_id' => $city->state_id,
                'country_id' => $state?->country_id,
            ];
        });
    }

    /**
     * Attach a specific city (and its state/country).
     *
     * Usage: Branch::factory()->forCity($city)->create();
     */
    public function forCity(City $city): static
    {
        return $this->state(function () use ($city) {
            $state = $city->state;

            return [
                'city_id' => $city->id,
                'state_id' => $city->state_id,
                'country_id' => $state?->country_id,
            ];
        });
    }

    /**
     * Attach a specific country only (no state/city).
     *
     * Usage: Branch::factory()->forCountry($country)->create();
     */
    public function forCountry(Country $country): static
    {
        return $this->state([
            'country_id' => $country->id,
            'state_id' => null,
            'city_id' => null,
        ]);
    }

    /**
     * Force a specific status.
     *
     * Usage: Branch::factory()->withStatus(BranchStatus::Active)->create();
     */
    public function withStatus(BranchStatus $status): static
    {
        return $this->state([
            'status' => $status->value,
        ]);
    }

    /**
     * Inactive branch.
     */
    public function inactive(): static
    {
        return $this->withStatus(BranchStatus::Inactive);
    }

    /**
     * Random status across all enum values.
     *
     * Usage: Branch::factory()->randomStatus()->create();
     */
    public function randomStatus(): static
    {
        return $this->state([
            'status' => $this->faker->randomElement(BranchStatus::values()),
        ]);
    }
}