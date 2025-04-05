<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Playlist;
use App\Models\PlaylistStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PlaylistStat> */
class PlaylistStatFactory extends Factory
{
    protected $model = PlaylistStat::class;

    public function definition(): array
    {
        return [
            'playlist_id' => Playlist::factory(),
            'total_matches' => $this->faker->numberBetween(1, 100),
            'total_players' => $this->faker->numberBetween(1, 100),
            'total_unique_players' => $this->faker->numberBetween(1, 100),
        ];
    }
}
