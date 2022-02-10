<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\Outcome;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MatchupTeam> */
class MatchupTeamFactory extends Factory
{
    protected $model = MatchupTeam::class;

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
