<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
  
    protected $model = City::class;

    public function definition()
    {
        return [
            'state_id' => State::inRandomOrder()->value('id')
                ?? State::factory(),   // fallback if no states exist
            'name' => $this->faker->unique()->city(),
            'description' => $this->faker->sentence(12),
        ];
    }
}
