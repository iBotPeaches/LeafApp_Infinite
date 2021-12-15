<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Playlist[]|Playlist create($attributes = [], ?Playlist $parent = null)
 * @method Collection|Playlist[] createMany(iterable $records)
 * @method Playlist createOne($attributes = [])
 * @method Collection|Playlist[]|Playlist make($attributes = [], ?Playlist $parent = null)
 * @method Playlist makeOne($attributes = [])
 */
class PlaylistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'version' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'thumbnail_url' => $this->faker->imageUrl,
            'is_ranked' => $this->faker->boolean,
            'queue' => Queue::getRandomValue(),
            'input' => Input::getRandomValue(),
        ];
    }
}
