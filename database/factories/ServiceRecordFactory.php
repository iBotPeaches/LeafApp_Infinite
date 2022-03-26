<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MedalDifficulty;
use App\Enums\Mode;
use App\Models\Medal;
use App\Models\Player;
use App\Models\ServiceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ServiceRecord> */
class ServiceRecordFactory extends Factory
{
    protected $model = ServiceRecord::class;

    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'mode' => Mode::MATCHMADE_RANKED,
            'kd' => $this->faker->numerify('#.##'),
            'kda' => $this->faker->numerify('#.##'),
            'total_score' => $this->faker->numerify('#####'),
            'total_matches' => $this->faker->numerify('###'),
            'matches_won' => $this->faker->numberBetween(50, 100),
            'matches_lost' => $this->faker->numberBetween(50, 100),
            'matches_tied' => $this->faker->numberBetween(0, 25),
            'matches_left' => $this->faker->numberBetween(0, 10),
            'total_seconds_played' => $this->faker->numerify('#####'),
            'kills' => $this->faker->numberBetween(1000, 2000),
            'deaths' => $this->faker->numberBetween(1000, 2000),
            'assists' => $this->faker->numberBetween(500, 1000),
            'betrayals' => $this->faker->numberBetween(0, 500),
            'suicides' => $this->faker->numberBetween(0, 500),
            'vehicle_destroys' => $this->faker->numberBetween(0, 25),
            'vehicle_hijacks' => $this->faker->numberBetween(0, 25),
            'medal_count' => $this->faker->numerify('####'),
            'damage_taken' => $this->faker->numerify('####'),
            'damage_dealt' => $this->faker->numerify('####'),
            'shots_fired' => $this->faker->numerify('####'),
            'shots_landed' => $this->faker->numerify('####'),
            'shots_missed' => $this->faker->numerify('####'),
            'accuracy' => $this->faker->numerify('##.##'),
            'kills_melee' => $this->faker->numberBetween(0, 25),
            'kills_grenade' => $this->faker->numberBetween(0, 25),
            'kills_headshot' => $this->faker->numberBetween(0, 25),
            'kills_power' => $this->faker->numberBetween(0, 25),
            'assists_emp' => $this->faker->numberBetween(0, 25),
            'assists_driver' => $this->faker->numberBetween(0, 25),
            'assists_callout' => $this->faker->numberBetween(0, 25),
        ];
    }

    public function withMedals(): self
    {
        return $this->state(function () {
            $medals = Medal::factory()
                ->sequence(
                    ['difficulty' => MedalDifficulty::LEGENDARY],
                    ['difficulty' => MedalDifficulty::MYTHIC],
                    ['difficulty' => MedalDifficulty::HEROIC],
                    ['difficulty' => MedalDifficulty::NORMAL],
                )
                ->count(8)
                ->create();

            return [
                // @phpstan-ignore-next-line
                'medals' => $medals->mapWithKeys(function (Medal $medal) {
                    return [
                        $medal->id => $this->faker->numberBetween(1, 25)
                    ];
                })->toArray()
            ];
        });
    }
}
