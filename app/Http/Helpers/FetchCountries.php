<?php

namespace App\Http\Helpers;

use App\Models\Country;

// In CountrySeeder
$countries = json_decode(file_get_contents(database_path('data/countries.json')), true);

foreach ($countries as $c) {
    Country::updateOrCreate(
        ['country_code' => $c['iso2']],
        ['name' => $c['name']]
    );
}