<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Category[]|Category create($attributes = [], ?Category $parent = null)
 * @method Collection|Category[] createMany(iterable $records)
 * @method Category createOne($attributes = [])
 * @method Collection|Category[]|Category make($attributes = [], ?Category $parent = null)
 * @method Category makeOne($attributes = [])
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'version' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'thumbnail_url' => $this->faker->imageUrl,
        ];
    }
}
