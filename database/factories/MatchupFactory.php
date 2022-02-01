<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Championship;
use App\Models\Matchup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Matchup[]|Matchup create($attributes = [], ?Matchup $parent = null)
 * @method Collection|Matchup[] createMany(iterable $records)
 * @method Matchup createOne($attributes = [])
 * @method Collection|Matchup[]|Matchup make($attributes = [], ?Matchup $parent = null)
 * @method Matchup makeOne($attributes = [])
 */
class MatchupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'faceit_id' => $this->faker->unique()->uuid,
            'championship_id' => Championship::factory(),
            'round' => 1,
            'group' => 1,
            'best_of' => 3,
            'started_at' => now(),
            'ended_at' => now(),
        ];
    }
}
