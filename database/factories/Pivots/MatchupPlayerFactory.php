<?php

declare(strict_types=1);

namespace Database\Factories\Pivots;

use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MatchupPlayer> */
class MatchupPlayerFactory extends Factory
{
    protected $model = MatchupPlayer::class;

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
