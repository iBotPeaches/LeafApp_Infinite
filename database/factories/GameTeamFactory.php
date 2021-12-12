<?php

namespace Database\Factories;

use App\Enums\Outcome;
use App\Models\Game;
use App\Models\GameTeam;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|GameTeam[]|GameTeam create($attributes = [], ?GameTeam $parent = null)
 * @method Collection|GameTeam[] createMany(iterable $records)
 * @method GameTeam createOne($attributes = [])
 * @method Collection|GameTeam[]|GameTeam make($attributes = [], ?GameTeam $parent = null)
 * @method GameTeam makeOne($attributes = [])
 */
class GameTeamFactory extends Factory
{
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
