<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        return [
            'country_code' => strtoupper($this->faker->unique()->countryCode),
            'name'         => $this->faker->unique()->country,
            'description'  => $this->faker->sentence(12),
        ];
    }

    /** A named state for tests. */
    public function bangladesh(): static
    {
        return $this->state(fn () => [
            'country_code' => 'BD',
            'name'         => 'Bangladesh',
            'description'  => 'South Asian country.',
        ]);
    }
}