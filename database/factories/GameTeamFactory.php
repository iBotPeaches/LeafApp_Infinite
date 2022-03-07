<?php

namespace Database\Factories;

use App\Enums\Outcome;
use App\Models\Game;
use App\Models\GameTeam;
use App\Models\MatchupTeam;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GameTeam> */
class GameTeamFactory extends Factory
{
    protected $model = GameTeam::class;

    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'internal_team_id' => 0,
            'name' => $this->faker->word,
            'emblem_url' => $this->faker->imageUrl,
            'outcome' => Outcome::WIN,
            'rank' => 1,
            'score' => $this->faker->numerify('####')
        ];
    }
}
