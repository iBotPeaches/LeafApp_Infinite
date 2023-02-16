<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Team> */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'internal_id' => random_int(1, 20),
            'name' => $this->faker->word,
            'emblem_url' => $this->faker->imageUrl,
        ];
    }
}
