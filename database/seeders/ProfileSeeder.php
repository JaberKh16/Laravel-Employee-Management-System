<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ============================================================
        // 1. Give every existing user a profile (idempotent)
        // ============================================================
        $usersWithoutProfile = User::doesntHave('profile')->get();

        if ($usersWithoutProfile->isNotEmpty()) {
            $this->command->info("Creating profiles for {$usersWithoutProfile->count()} existing users…");

            foreach ($usersWithoutProfile as $user) {
                Profile::factory()
                    ->forUser($user)
                    ->when(
                        City::exists(),
                        fn($factory) => $factory->withLocation(),
                        fn($factory) => $factory
                    )
                    ->create();
            }
        }

        // ============================================================
        // 2. Seed a fixed set of demo users with nice profiles
        // ============================================================
        $demoProfiles = [
            [
                'username' => 'johndoe',
                'email' => 'john.doe@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'male',
                'bio' => 'Full-stack developer with 10+ years of experience.',
                'website' => 'https://johndoe.dev',
                'linkedin' => 'https://linkedin.com/in/johndoe',
            ],
            [
                'username' => 'janesmith',
                'email' => 'jane.smith@example.com',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'gender' => 'female',
                'bio' => 'Product designer. Loves coffee and clean UI.',
                'twitter' => 'https://twitter.com/janesmith',
            ],
            [
                'username' => 'bobjohnson',
                'email' => 'bob.johnson@example.com',
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'gender' => 'male',
                'bio' => 'DevOps engineer. Kubernetes enthusiast.',
                'website' => 'https://bobjohnson.io',
            ],
            [
                'username' => 'alicewilliams',
                'email' => 'alice.williams@example.com',
                'first_name' => 'Alice',
                'last_name' => 'Williams',
                'gender' => 'female',
                'bio' => 'QA lead. Bug hunter by day, gamer by night.',
            ],
            [
                'username' => 'charliebrown',
                'email' => 'charlie.brown@example.com',
                'first_name' => 'Charlie',
                'last_name' => 'Brown',
                'gender' => 'male',
                'bio' => 'Backend engineer. Go and Rust.',
            ],
        ];

        foreach ($demoProfiles as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'status' => 1,
                ]
            );

            // Skip if this user already has a profile
            if ($user->profile()->exists()) {
                continue;
            }

            $profileData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'bio' => $data['bio'],
                'phone' => fake()->phoneNumber(),
                'birthdate' => fake()->dateTimeBetween('-60 years', '-22 years'),
                'address' => fake()->streetAddress(),
                'zip_code' => fake()->postcode(),
                'website' => $data['website'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'twitter' => $data['twitter'] ?? null,
            ];

            // Attach location if cities exist
            if (City::exists()) {
                $city = City::inRandomOrder()->first();
                $profileData['city_id'] = $city->id;
                $profileData['state_id'] = $city->state_id;
                $profileData['country_id'] = $city->state?->country_id ?? null;
            }

            $user->profile()->create($profileData);
        }

        // ============================================================
        // 3. Generate extra random profiles (optional)
        // ============================================================
        if (app()->environment('local')) {
            $extra = 15;

            $this->command->info("Creating {$extra} random profiles…");

            Profile::factory()
                ->count($extra)
                ->when(
                    City::exists(),
                    fn($factory) => $factory->withLocation()
                )
                ->create();
        }
    }
}