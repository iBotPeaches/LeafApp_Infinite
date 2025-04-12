<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Playlist> */
class PlaylistFactory extends Factory
{
    protected $model = Playlist::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'is_active' => true,
            'is_ranked' => $this->faker->boolean,
            'queue' => Queue::getRandomValue(),
            'input' => Input::getRandomValue(),
            'rotations' => [
                [
                    'name' => 'Arena:Slayer on Map',
                    'weight' => 100,
                ],
                [
                    'name' => 'Arena:CTF on Map',
                    'weight' => 110,
                ],
                [
                    'name' => 'Super Husky Raid:CTF on Map',
                    'weight' => 110,
                ],
            ],
            'image_url' => $this->faker->imageUrl,
        ];
    }
}
