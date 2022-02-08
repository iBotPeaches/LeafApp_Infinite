<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Outcome;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTeam;
use App\Models\Medal;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|GamePlayer[]|GamePlayer create($attributes = [], ?GamePlayer $parent = null)
 * @method Collection|GamePlayer[] createMany(iterable $records)
 * @method GamePlayer createOne($attributes = [])
 * @method Collection|GamePlayer[]|GamePlayer make($attributes = [], ?GamePlayer $parent = null)
 * @method GamePlayer makeOne($attributes = [])
 */
class GamePlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'game_team_id' => GameTeam::factory(),
            'game_id' => Game::factory(),
            'pre_csr' => $this->faker->numberBetween(600, 1500),
            'post_csr' => $this->faker->numberBetween(600, 1500),
            'rank' => $this->faker->numberBetween(1, 8),
            'outcome' => Outcome::getRandomValue(),
            'was_at_start' => $this->faker->boolean,
            'was_at_end' => $this->faker->boolean,
            'was_inprogress_join' => $this->faker->boolean,
            'kd' => $this->faker->numerify('#.##'),
            'kda' => $this->faker->numerify('#.##'),
            'score' => $this->faker->numerify('####'),
            'kills' => $this->faker->numberBetween(1, 25),
            'deaths' => $this->faker->numberBetween(1, 25),
            'assists' => $this->faker->numberBetween(0, 10),
            'betrayals' => $this->faker->numberBetween(0, 5),
            'suicides' => $this->faker->numberBetween(0, 2),
            'vehicle_destroys' => $this->faker->numberBetween(0, 2),
            'vehicle_hijacks' => $this->faker->numberBetween(0, 2),
            'medal_count' => $this->faker->numberBetween(5, 25),
            'damage_taken' => $this->faker->numerify('####'),
            'damage_dealt' => $this->faker->numerify('####'),
            'shots_fired' => $this->faker->numerify('####'),
            'shots_landed' => $this->faker->numerify('####'),
            'shots_missed' => $this->faker->numerify('####'),
            'accuracy' => $this->faker->numerify('##.##'),
            'rounds_won' => $this->faker->numberBetween(0, 2),
            'rounds_lost' => $this->faker->numberBetween(0, 2),
            'rounds_tied' => $this->faker->numberBetween(0, 2),
            'kills_melee' => $this->faker->numberBetween(0, 10),
            'kills_grenade' => $this->faker->numberBetween(0, 10),
            'kills_headshot' => $this->faker->numberBetween(0, 10),
            'kills_power' => $this->faker->numberBetween(0, 10),
            'assists_emp' => $this->faker->numberBetween(0, 10),
            'assists_driver' => $this->faker->numberBetween(0, 10),
            'assists_callout' => $this->faker->numberBetween(0, 10),
        ];
    }

    public function withMedals(): self
    {
        return $this->state(function (array $attributes) {
            // @phpstan-ignore-next-line
            $medals = Medal::all()->isEmpty()
                ? Medal::factory()->count(2)->create()
                : Medal::all();

            return [
                // @phpstan-ignore-next-line
                'medals' => $medals->mapWithKeys(function (Medal $medal) {
                    return [
                        $medal->id => $this->faker->numberBetween(1, 25)
                    ];
                })->toArray()
            ];
        });
    }
}
