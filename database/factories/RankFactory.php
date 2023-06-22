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
            'name' => $this->faker->word,
        ];
    }
}
