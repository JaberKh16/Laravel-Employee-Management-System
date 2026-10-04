<?php

// namespace Database\Factories;

// use App\Models\Country;
// use App\Models\State;
// use Illuminate\Database\Eloquent\Factories\Factory;

// class StateFactory extends Factory
// {
//     protected $model = State::class;

//     /**
//      * Curated "real-ish" state codes. Used first, then padded with generated ones.
//      */
//     protected const STATE_CODES = [
//         'US-CA',
//         'US-NY',
//         'US-TX',
//         'US-FL',
//         'US-WA',
//         'CA-ON',
//         'CA-QC',
//         'CA-BC',
//         'CA-AB',
//         'AU-NSW',
//         'AU-VIC',
//         'AU-QLD',
//         'AU-WA',
//         'IN-MH',
//         'IN-KA',
//         'IN-DL',
//         'IN-TN',
//         'GB-ENG',
//         'GB-SCT',
//         'GB-WLS',
//         'GB-NIR',
//     ];

//     /**
//      * Real US state names, used as the primary pool for `name`.
//      * Faker has a `state()` formatter but it lives in the Address
//      * provider and isn't guaranteed to be loaded in every locale.
//      */
//     protected const STATE_NAMES = [
//         'California',
//         'New York',
//         'Texas',
//         'Florida',
//         'Washington',
//         'Ontario',
//         'Quebec',
//         'British Columbia',
//         'Alberta',
//         'New South Wales',
//         'Victoria',
//         'Queensland',
//         'Western Australia',
//         'Maharashtra',
//         'Karnataka',
//         'Delhi',
//         'Tamil Nadu',
//         'England',
//         'Scotland',
//         'Wales',
//         'Northern Ireland',
//     ];

//     /**
//      * Cached pool so we don't rebuild it for every factory call.
//      *
//      * @var array<int, string>|null
//      */
//     protected static ?array $codePool = null;

//     public function definition(): array
//     {
//         return [
//             'country_id' => Country::factory(),
//             'name' => $this->faker->unique()->randomElement(self::STATE_NAMES)
//                 . ' ' . $this->faker->unique()->numberBetween(1, 99999),
//             'description' => $this->faker->sentence(12),
//             'state_code' => $this->faker->unique()->randomElement($this->codePool()),
//         ];
//     }

//     /**
//      * Curated codes + generated padding, memoized.
//      *
//      * @return array<int, string>
//      */
//     protected function codePool(): array
//     {
//         if (self::$codePool === null) {
//             self::$codePool = array_values(array_unique(array_merge(
//                 self::STATE_CODES,
//                 $this->generateExtraCodes(500)
//             )));
//         }

//         return self::$codePool;
//     }

//     /**
//      * Generate N fake but plausible state codes, e.g. "XX-AAB".
//      *
//      * @return array<int, string>
//      */
//     protected function generateExtraCodes(int $count): array
//     {
//         $codes = [];

//         // Faker's lexify() replaces '?' with a random lowercase letter.
//         // We uppercase the whole thing afterward so codes look like
//         // the curated ones (US-CA, GB-ENG).
//         for ($i = 0; $i < $count; $i++) {
//             $codes[] = strtoupper(
//                 $this->faker->lexify('??') . '-' . $this->faker->lexify('???')
//             );
//         }

//         return $codes;
//     }

//     /**
//      * Named state for tests and deterministic seeds.
//      */
//     public function bangladesh(): static
//     {
//         return $this->state(fn() => [
//             'name' => 'Dhaka',
//             'state_code' => 'BD-13',
//             'description' => 'Capital division of Bangladesh.',
//         ]);
//     }
// }



namespace Database\Factories;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\State>
 */
class StateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\State>
     */
    protected $model = State::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Prefer a real, existing country. Only create one if none exist —
        // this keeps the factory usable in isolation (e.g. StateTest)
        // without generating a country per state.
        $countryId = Country::query()->inRandomOrder()->value('id');

        if (!$countryId) {
            $countryId = Country::factory()->create()->id;
        }

        $name = $this->faker->unique()->state();

        return [
            'country_id' => $countryId,
            'name' => $name,
            // Short uppercase code derived from the name, e.g. "CAL", "NEW".
            // Not guaranteed unique across runs but good enough for seeding;
            // tests that care about uniqueness should override it explicitly.
            'state_code' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3)),
            'description' => $this->faker->optional(0.7)->sentence(10),
        ];
    }

    /**
     * Indicate that the state belongs to the given country.
     */
    public function forCountry(Country $country): static
    {
        return $this->state(fn() => ['country_id' => $country->id]);
    }
}