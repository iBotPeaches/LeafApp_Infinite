<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\Outcome;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|MatchupTeam[]|MatchupTeam create($attributes = [], ?MatchupTeam $parent = null)
 * @method Collection|MatchupTeam[] createMany(iterable $records)
 * @method MatchupTeam createOne($attributes = [])
 * @method Collection|MatchupTeam[]|MatchupTeam make($attributes = [], ?MatchupTeam $parent = null)
 * @method MatchupTeam makeOne($attributes = [])
 */
class MatchupTeamFactory extends Factory
{
    public function definition(): array
    {
        $outcome = Outcome::getRandomValue();

        return [
            'matchup_id' => Matchup::factory(),
            'faceit_id' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'points' => $outcome === Outcome::WIN ? 3 : 0,
            'outcome' => $outcome
        ];
    }

    public function bye(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'faceit_id' => MatchupTeam::$byeTeamId,
                'name' => 'bye'
            ];
        });
    }
}
