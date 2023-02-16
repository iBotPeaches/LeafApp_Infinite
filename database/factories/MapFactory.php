<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Map> */
class MapFactory extends Factory
{
    protected $model = Map::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'thumbnail_url' => $this->faker->imageUrl,
        ];
    }
}
