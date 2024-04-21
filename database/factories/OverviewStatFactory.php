<?php

namespace Database\Factories;

use App\Models\Overview;
use App\Models\OverviewStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OverviewStat> */
class OverviewStatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'overview_id' => Overview::factory(),
            'overview_gametype_id' => null,
            'overview_map_id' => null,
            'total_matches' => $this->faker->numberBetween(1, 100),
            'total_seconds_played' => $this->faker->numberBetween(1, 100),
            'total_players' => $this->faker->numberBetween(1, 100),
            'total_unique_players' => $this->faker->numberBetween(1, 100),
            'total_dnf' => $this->faker->numberBetween(1, 100),
            'total_kills' => $this->faker->numberBetween(1, 100),
            'total_deaths' => $this->faker->numberBetween(1, 100),
            'total_assists' => $this->faker->numberBetween(1, 100),
            'total_suicides' => $this->faker->numberBetween(1, 100),
            'total_medals' => $this->faker->numberBetween(1, 100),
            'average_kd' => $this->faker->randomFloat(2, 0, 10),
            'average_kda' => $this->faker->randomFloat(2, 0, 10),
            'average_accuracy' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
