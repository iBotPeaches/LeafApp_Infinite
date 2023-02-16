<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Category> */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'thumbnail_url' => $this->faker->imageUrl,
        ];
    }
}
