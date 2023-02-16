<?php

namespace Database\Factories;

use App\Enums\Outcome;
use App\Models\Game;
use App\Models\GameTeam;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GameTeam> */
class GameTeamFactory extends Factory
{
    protected $model = GameTeam::class;

    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'team_id' => Team::factory(),
            'internal_team_id' => 0,
            'outcome' => Outcome::WIN,
            'rank' => 1,
            'score' => $this->faker->numerify('####'),
        ];
    }
}
