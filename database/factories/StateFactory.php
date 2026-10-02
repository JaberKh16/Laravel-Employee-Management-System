<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    protected $model = State::class;

    /**
     * Curated "real-ish" state codes. Used first, then padded with generated ones.
     */
    protected const STATE_CODES = [
        'US-CA',
        'US-NY',
        'US-TX',
        'US-FL',
        'US-WA',
        'CA-ON',
        'CA-QC',
        'CA-BC',
        'CA-AB',
        'AU-NSW',
        'AU-VIC',
        'AU-QLD',
        'AU-WA',
        'IN-MH',
        'IN-KA',
        'IN-DL',
        'IN-TN',
        'GB-ENG',
        'GB-SCT',
        'GB-WLS',
        'GB-NIR',
    ];

    /**
     * Cached pool so we don't rebuild it for every factory call.
     *
     * @var array<int, string>|null
     */
    protected static ?array $codePool = null;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'name' => $this->faker->state(),
            'description' => $this->faker->sentence(12),
            'state_code' => $this->faker->unique()->randomElement($this->codePool()),
            // 'state_code' => $this->faker->unique()->randomElement(self::STATE_CODES),
        ];
    }

    /**
     * Curated codes + generated padding, memoized.
     *
     * @return array<int, string>
     */
    protected function codePool(): array
    {
        if (self::$codePool === null) {
            self::$codePool = array_merge(
                self::STATE_CODES,
                $this->generateExtraCodes(500)
            );
        }

        return self::$codePool;
    }

    /**
     * Generate N fake but plausible state codes, e.g. "XX-AAB".
     *
     * @return array<int, string>
     */
    protected function generateExtraCodes(int $count): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(
                $this->faker->lexify('??-') . $this->faker->lexify('???')
            );
        }

        return array_values(array_unique($codes));
    }
}