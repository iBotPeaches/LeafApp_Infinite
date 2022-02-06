<?php
declare(strict_types = 1);

namespace Database\Factories\Pivots;

use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupGame;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|MatchupGame[]|MatchupGame create($attributes = [], ?MatchupGame $parent = null)
 * @method Collection|MatchupGame[] createMany(iterable $records)
 * @method MatchupGame createOne($attributes = [])
 * @method Collection|MatchupGame[]|MatchupGame make($attributes = [], ?MatchupGame $parent = null)
 * @method MatchupGame makeOne($attributes = [])
 */
class MatchupGameFactory extends Factory
{
    protected $model = MatchupGame::class;

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
