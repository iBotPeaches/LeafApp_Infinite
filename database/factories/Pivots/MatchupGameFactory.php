<?php
declare(strict_types = 1);

namespace Database\Factories\Pivots;

use App\Models\Game;
use App\Models\Matchup;
use App\Models\Pivots\MatchupGame;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MatchupGame> */
class MatchupGameFactory extends Factory
{
    protected $model = MatchupGame::class;

    public function definition(): array
    {
        return [
            'matchup_id' => Matchup::factory(),
            'game_id' => Game::factory(),
        ];
    }
}
