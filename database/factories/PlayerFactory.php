<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Player[]|Player create($attributes = [], ?Player $parent = null)
 * @method Collection|Player[] createMany(iterable $records)
 * @method Player createOne($attributes = [])
 * @method Collection|Player[]|Player make($attributes = [], ?Player $parent = null)
 * @method Player makeOne($attributes = [])
 */
class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'xuid' => $this->faker->numerify('################'),
            'gamertag' => $this->faker->word . $this->faker->unixTime,
            'service_tag' => $this->faker->lexify,
            'is_private' => false,
            'emblem_url' => $this->faker->url,
            'backdrop_url' => $this->faker->url
        ];
    }
}
