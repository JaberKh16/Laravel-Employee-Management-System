<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        // Allow overriding via CLI: php artisan db:seed --class=CountrySeeder
        // Or: COUNT=50 php artisan db:seed
        $default = (int) env('COUNT', 20);

        $count = $this->command?->ask(
            'How many "country" seeds would you like to create?',
            $default
        ) ?? $default;

        $count = max(0, (int) $count); // guard against negative / non-numeric input

        if ($count === 0) {
            $this->command?->warn('Skipped: seed count is 0.');
            return;
        }

        Country::factory($count)->create();

        $this->command?->info("Seeded {$count} countries.");
    }
}