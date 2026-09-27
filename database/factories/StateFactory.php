<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = State::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected static array $stateCodes = [
        // US
        'US-CA',
        'US-NY',
        'US-TX',
        'US-FL',
        'US-WA',
        // Canada
        'CA-ON',
        'CA-QC',
        'CA-BC',
        'CA-AB',
        // Australia
        'AU-NSW',
        'AU-VIC',
        'AU-QLD',
        'AU-WA',
        // India
        'IN-MH',
        'IN-KA',
        'IN-DL',
        'IN-TN',
        // UK
        'GB-ENG',
        'GB-SCT',
        'GB-WLS',
        'GB-NIR',
    ];

    public function definition()
    {
        return [
            'country_id' => Country::select('id')->get()->random()->id,
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(12),
            // 'state_code' => strtoupper($this->faker->lexify('???')),

            'state_code' => $this->faker->unique()->randomElement(self::$stateCodes)
        ];
    }
}
