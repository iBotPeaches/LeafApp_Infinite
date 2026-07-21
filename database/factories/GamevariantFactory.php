<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Gamevariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Gamevariant> */
class GamevariantFactory extends Factory
{
    protected $model = Gamevariant::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'uuid' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
        ];
    }
}
