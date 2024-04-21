<?php

namespace Database\Factories;

use App\Models\Overview;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Overview> */
class OverviewFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->word;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'thumbnail_url' => $this->faker->imageUrl(),
        ];
    }
}
