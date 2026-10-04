<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\Profile;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            // Each profile belongs to a fresh user unless overridden
            'user_id' => User::factory(),

            // Names — mutators will capitalize, but we pass proper case anyway
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),

            'phone' => $this->faker->phoneNumber(),
            'avatar' => null,   // null → accessor falls back to ui-avatars
            'birthdate' => $this->faker->dateTimeBetween('-65 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'bio' => $this->faker->paragraph(2),

            // Location — random IDs if tables are seeded; null otherwise
            'address' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->postcode(),
            'country_id' => null,
            'state_id' => null,
            'city_id' => null,

            // Social
            'website' => $this->faker->boolean(30) ? $this->faker->url() : null,
            'linkedin' => $this->faker->boolean(20) ? 'https://linkedin.com/in/' . $this->faker->userName() : null,
            'twitter' => $this->faker->boolean(20) ? 'https://twitter.com/' . $this->faker->userName() : null,
        ];
    }

    // ============================================================
    // STATES
    // ============================================================

    /**
     * Assign a real country/state/city from the database.
     * Usage: Profile::factory()->withLocation()->create();
     */
    public function withLocation(): static
    {
        return $this->state(function () {
            $city = City::inRandomOrder()->first()
                ?? City::factory()->create();

            // Walk up the chain: city → state → country
            $state = $city->state;

            return [
                'country_id' => $state?->country_id,
                'state_id' => $city->state_id,
                'city_id' => $city->id,
            ];
        });
    }

    /**
     * Force a specific country (picks a random state/city inside it).
     * Usage: Profile::factory()->forCountry($countryId)->create();
     */
    public function forCountry(int $countryId): static
    {
        return $this->state(function () use ($countryId) {
            $state = State::where('country_id', $countryId)->inRandomOrder()->first();
            $city = $state ? City::where('state_id', $state->id)->inRandomOrder()->first() : null;

            return [
                'country_id' => $countryId,
                'state_id' => $state?->id,
                'city_id' => $city?->id,
            ];
        });
    }

    /**
     * Attach an existing user instead of creating one.
     * Usage: Profile::factory()->forUser($user)->create();
     */
    public function forUser(User $user): static
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }

    /**
     * Minimal profile — only the required bits.
     * Usage: Profile::factory()->minimal()->create();
     */
    public function minimal(): static
    {
        return $this->state([
            'phone' => null,
            'avatar' => null,
            'birthdate' => null,
            'gender' => null,
            'bio' => null,
            'address' => null,
            'zip_code' => null,
            'website' => null,
            'linkedin' => null,
            'twitter' => null,
        ]);
    }

    /**
     * Profile with a real external avatar URL.
     * Usage: Profile::factory()->withAvatar()->create();
     */
    public function withAvatar(): static
    {
        return $this->state([
            'avatar' => 'https://i.pravatar.cc/300?u=' . $this->faker->uuid(),
        ]);
    }
}