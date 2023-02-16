<?php

declare(strict_types=1);

namespace Database\Factories\Pivots;

use App\Models\Game;
use App\Models\Pivots\GameScrim;
use App\Models\Scrim;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GameScrim> */
class GameScrimFactory extends Factory
{
    protected $model = GameScrim::class;

    public function definition(): array
    {
        return [
            'scrim_id' => Scrim::factory(),
            'game_id' => Game::factory(),
        ];
    }
}
