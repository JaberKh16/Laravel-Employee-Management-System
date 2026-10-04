<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    protected FakerGenerator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    // public function run()
    // {
    //     // Admin user
    //     $admin = User::updateOrCreate([
    //         'username' => 'admin',
    //         'first_name' => 'admin',
    //         'last_name' => 'user',
    //         'email' => 'admin@gmail.com',
    //         'email_verified_at' => now(),
    //         'password' => Hash::make(12345678), // 12345678
    //         'remember_token' => Str::random(10),
    //     ]);
    //     $admin->assignRole('Admin');

    //     // Normal User
    //     $normal = User::updateOrCreate([
    //         'username' => 'user',
    //         'first_name' => 'user',
    //         'last_name' => 'user',
    //         'email' => 'user@gmail.com',
    //         'email_verified_at' => now(),
    //         'password' => Hash::make(12345678), // 12345678
    //         'remember_token' => Str::random(10),
    //     ]);
    //     $normal->assignRole('User');
    //     //Interactive database seeding
    //     $seedCount = (int) $this->command->ask('How many "users" seeds would you like to create?', 20);
    //     User::factory($seedCount)->create();
    // }

    public function run(): void
    {
        // ============================================================
        // 1. Admin user
        // ============================================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'status' => 1,
                'remember_token' => Str::random(10),
            ]
        );

        // Assign role (Spatie) — safe if already assigned
        if (method_exists($admin, 'assignRole') && !$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        // Fill the auto-created profile
        $this->upsertProfile($admin, [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'gender' => 'male',
            'birthdate' => now()->subYears(30),
        ]);

        // ============================================================
        // 2. Normal user
        // ============================================================
        $normal = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'username' => 'user',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'status' => 1,
                'remember_token' => Str::random(10),
            ]
        );

        if (method_exists($normal, 'assignRole') && !$normal->hasRole('User')) {
            $normal->assignRole('User');
        }

        $this->upsertProfile($normal, [
            'first_name' => 'Normal',
            'last_name' => 'User',
            'gender' => 'male',
            'birthdate' => now()->subYears(25),
        ]);

        // ============================================================
        // 3. Interactive batch
        // ============================================================
        $seedCount = (int) $this->command->ask(
            'How many additional users would you like to create?',
            20
        );

        if ($seedCount > 0) {
            $this->command->info("Creating {$seedCount} users with profiles…");

            User::factory()
                ->count($seedCount)
                ->create()
                ->each(function (User $user, int $i) {
                    // booted() already created an empty profile — fill it now
                    $this->upsertProfile($user, [
                        'first_name' => $this->faker->firstName(),
                        'last_name' => $this->faker->lastName(),
                        'gender' => $this->faker->randomElement(['male', 'female', 'other']),
                        'birthdate' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
                        'phone' => $this->faker->boolean(70) ? $this->faker->phoneNumber() : null,
                        'bio' => $this->faker->boolean(60) ? $this->faker->sentence(10) : null,
                    ]);
                });

            $this->command->info("✓ {$seedCount} users + profiles created.");
        }

        // ============================================================
        // 4. Safety net — backfill any user missing a profile
        // ============================================================
        $missing = User::doesntHave('profile')->count();
        if ($missing > 0) {
            $this->command->warn("Backfilling {$missing} users that were missing a profile…");

            User::doesntHave('profile')->each(function (User $user) {
                $user->profile()->create([
                    'first_name' => $user->username,
                    'last_name' => '',
                ]);
            });
        }
    }

    /**
     * Fill (or create) the profile attached to a user.
     * Safe to call multiple times — updates existing rows.
     */
    protected function upsertProfile(User $user, array $data): Profile
    {
        // Names — strip nulls so we don't overwrite existing values
        $data = array_filter($data, fn($v) => $v !== null);

        return $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    }
}