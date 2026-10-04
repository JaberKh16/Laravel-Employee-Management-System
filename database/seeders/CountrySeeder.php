<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CountrySeeder extends Seeder
{
    // public function run(): void
    // {
    //     // Resolve count from (in priority order):
    //     //   1. --count=N CLI flag (via $this->command->option)
    //     //   2. COUNT env var
    //     //   3. default of 20
    //     //
    //     // We deliberately avoid ask() because:
    //     //   - It hangs in non-interactive contexts (CI, Docker)
    //     //   - It silently falls back to default in --no-interaction mode
    //     //   - It breaks `migrate:fresh --seed` pipelines
    //     $default = 20;

    //     $count = (int) (env('COUNT') ?? $default);
    //     $count = max(0, $count);

    //     if ($count === 0) {
    //         $this->command?->warn('CountrySeeder skipped: count is 0.');
    //         return;
    //     }

    //     // Wrap in a transaction so a partial failure doesn't leave
    //     // an inconsistent seed. Useful if you ever seed related rows
    //     // (states, cities) in the same run.
    //     DB::transaction(function () use ($count) {
    //         Country::factory($count)->create();
    //     });

    //     $this->command?->info("Seeded {$count} countries.");
    // }



    // public function run(): void
    // {
    //     $countries = [
    //         ['country_code' => 'BD', 'name' => 'Bangladesh',    'description' => 'South Asian country.'],
    //         ['country_code' => 'US', 'name' => 'United States', 'description' => 'North American country.'],
    //         ['country_code' => 'IN', 'name' => 'India',         'description' => 'South Asian country.'],
    //         // ... etc
    //     ];

    //     DB::transaction(function () use ($countries) {
    //         Country::upsert($countries, ['country_code'], ['name', 'description']);
    //     });

    //     $this->command?->info('Seeded ' . count($countries) . ' countries.');
    // }


    // api based fetched
    public function run(): void
    {
        // IANA's public-domain ISO 3166-1 list (code + English name)
        $url = 'https://data.iana.org/time-zones/tzdb-2026b/iso3166.tab';

        $response = Http::get($url);

        if (!$response->successful()) {
            $this->command?->error('Failed to download ISO 3166-1 list from IANA.');
            return;
        }

        $countries = [];

        foreach (explode("\n", $response->body()) as $line) {
            $line = trim($line);

            // Skip comments, blank lines, and the header
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Format: "BD\tBangladesh"
            $parts = preg_split('/\t+/', $line, 2);

            if (count($parts) !== 2) {
                continue;
            }

            [$code, $name] = $parts;

            // IANA uses "Britain (UK)" etc. Normalize a few common ones
            $name = match ($code) {
                'GB' => 'United Kingdom',
                'US' => 'United States',
                'KR' => 'South Korea',
                'KP' => 'North Korea',
                default => $name,
            };

            $countries[] = [
                'country_code' => strtoupper($code),
                'name' => $name,
                'description' => null,
            ];
        }

        DB::transaction(function () use ($countries) {
            Country::upsert(
                $countries,
                ['country_code'],
                ['name']
            );
        });

        $this->command?->info('Seeded ' . count($countries) . ' real countries.');
    }
}