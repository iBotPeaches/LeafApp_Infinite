<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Playlist;
use App\Models\PlaylistChange;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PlaylistChange> */
class PlaylistChangeFactory extends Factory
{
    protected $model = PlaylistChange::class;

    public function definition(): array
    {
        return [
            'playlist_id' => Playlist::factory(),
            'rotation_hash' => $this->faker->sha256,
            'rotations' => [
                [
                    'name' => 'Arena:Slayer on Map',
                    'weight' => 100,
                ],
                [
                    'name' => 'Arena:CTF on Map',
                    'weight' => 110,
                ],
            ],
        ];
    }
}
