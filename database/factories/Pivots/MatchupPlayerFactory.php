<?php
declare(strict_types = 1);

namespace Database\Factories\Pivots;

use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|MatchupPlayer[]|MatchupPlayer create($attributes = [], ?MatchupPlayer $parent = null)
 * @method Collection|MatchupPlayer[] createMany(iterable $records)
 * @method MatchupPlayer createOne($attributes = [])
 * @method Collection|MatchupPlayer[]|MatchupPlayer make($attributes = [], ?MatchupPlayer $parent = null)
 * @method MatchupPlayer makeOne($attributes = [])
 */
class MatchupPlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'matchup_team_id' => MatchupTeam::factory(),
            'player_id' => Player::factory(),
            'faceit_id' => $this->faker->unique()->uuid,
            'faceit_name' => $this->faker->word,
        ];
    }
}
