<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\MedalCategory;
use App\Enums\MedalType;
use App\Models\Medal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Medal[]|Medal create($attributes = [], ?Medal $parent = null)
 * @method Collection|Medal[] createMany(iterable $records)
 * @method Medal createOne($attributes = [])
 * @method Collection|Medal[]|Medal make($attributes = [], ?Medal $parent = null)
 * @method Medal makeOne($attributes = [])
 */
class MedalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'category' => MedalCategory::getRandomValue(),
            'type' => MedalType::getRandomValue(),
            'thumbnail_url' => $this->faker->imageUrl,
        ];
    }
}
