<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\City>
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Prefer a real, existing state. Only fall back to a factory-created
        // state if the table is empty — that keeps tests self-contained
        // without polluting the DB with hundreds of extra states.
        $stateId = State::query()->inRandomOrder()->value('id');

        if (!$stateId) {
            $stateId = State::factory()->create()->id;
        }

        return [
            'state_id' => $stateId,
            'name' => $this->faker->unique()->city(),
            'description' => $this->faker->sentence(12),
        ];
    }

    /**
     * Indicate that the city belongs to the given state.
     */
    public function forState(State $state): static
    {
        return $this->state(fn() => ['state_id' => $state->id]);
    }

    /**
     * Indicate that the city has no description.
     */
    public function withoutDescription(): static
    {
        return $this->state(fn() => ['description' => null]);
    }
}