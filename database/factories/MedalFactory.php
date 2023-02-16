<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MedalDifficulty;
use App\Enums\MedalType;
use App\Models\Medal;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Medal> */
class MedalFactory extends Factory
{
    protected $model = Medal::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->numerify('#########'),
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'type' => MedalType::getRandomValue(),
            'difficulty' => MedalDifficulty::getRandomValue(),
        ];
    }
}
