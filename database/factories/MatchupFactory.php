<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Championship;
use App\Models\Matchup;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Matchup> */
class MatchupFactory extends Factory
{
    protected $model = Matchup::class;

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
