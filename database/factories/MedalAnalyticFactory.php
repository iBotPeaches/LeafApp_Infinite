<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Mode;
use App\Models\Medal;
use App\Models\MedalAnalytic;
use App\Models\Player;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MedalAnalytic> */
class MedalAnalyticFactory extends Factory
{
    protected $model = MedalAnalytic::class;

    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'season_id' => Season::factory(),
            'medal_id' => Medal::factory(),
            'mode' => Mode::MATCHMADE_PVP,
            'place' => 1,
            'value' => $this->faker->randomFloat(2, 0, 20),
            'total_seconds_played' => $this->faker->numberBetween(1, 600)
        ];
    }
}
