<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnalyticKey;
use App\Models\Game;
use App\Models\Player;
use App\Models\Playlist;
use App\Models\PlaylistAnalytic;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PlaylistAnalytic> */
class PlaylistAnalyticFactory extends Factory
{
    protected $model = PlaylistAnalytic::class;

    public function definition(): array
    {
        return [
            'playlist_id' => Playlist::factory(),
            'game_id' => Game::factory(),
            'player_id' => Player::factory(),
            'key' => AnalyticKey::MOST_DEATHS_GAME->value,
            'place' => 1,
            'value' => $this->faker->randomFloat(2, 0, 20),
            'label' => $this->faker->word,
        ];
    }
}
