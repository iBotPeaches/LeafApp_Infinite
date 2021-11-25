<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'xuid' => $this->faker->numerify('################'),
            'gamertag' => $this->faker->word,
            'service_tag' => $this->faker->lexify,
            'emblem_url' => $this->faker->url,
            'backdrop_url' => $this->faker->url
        ];
    }
}
