<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Rank> */
class RankFactory extends Factory
{
    protected $model = Rank::class;

    public function definition(): array
    {
        return [
            'id' => 1,
            'name' => $this->faker->word,
            'subtitle' => $this->faker->word,
            'grade' => 1,
            'tier' => 1,
            'type' => 'Bronze',
            'threshold' => 1510,
            'required' => 100,
        ];
    }
}
