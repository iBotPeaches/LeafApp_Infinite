<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Championship;
use App\Services\FaceIt\Enums\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Championship> */
class ChampionshipFactory extends Factory
{
    protected $model = Championship::class;

    public function definition(): array
    {
        return [
            'faceit_id' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'region' => Region::getRandomValue(),
            'started_at' => now()
        ];
    }
}
