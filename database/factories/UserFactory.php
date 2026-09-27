<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Cache the hashed password so we don't bcrypt 20+ times.
     */
    protected static ?string $password = null;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'status' => 1,   // 1 = active
            'remember_token' => Str::random(10),

            // Optional FK columns from your migration
            'user_id' => null,
            'profile_id' => null,
            'dept_id' => null,
            'created_by' => null,
            'updated_by' => null,

        ];
    }

    /**
     * Unverified user.
     */
    public function unverified(): static
    {
        return $this->state(fn() => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Inactive user.
     */
    public function inactive(): static
    {
        return $this->state(fn() => [
            'status' => 0,
        ]);
    }

    /**
     * Assign a role after creation.
     */
    public function withRole(string $role): static
    {
        return $this->afterCreating(function (User $user) use ($role) {
            if (method_exists($user, 'assignRole')) {
                $user->assignRole($role);
            }
        });
    }
}
