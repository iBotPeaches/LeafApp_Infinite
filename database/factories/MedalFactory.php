<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\MedalCategory;
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
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'category' => MedalCategory::getRandomValue(),
            'type' => MedalType::getRandomValue(),
            'thumbnail_url' => $this->faker->imageUrl,
        ];
    }
}
