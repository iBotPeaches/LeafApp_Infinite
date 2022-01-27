<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Championship;
use App\Services\FaceIt\Enums\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Championship[]|Championship create($attributes = [], ?Championship $parent = null)
 * @method Collection|Championship[] createMany(iterable $records)
 * @method Championship createOne($attributes = [])
 * @method Collection|Championship[]|Championship make($attributes = [], ?Championship $parent = null)
 * @method Championship makeOne($attributes = [])
 */
class ChampionshipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'faceit_id' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'region' => Region::getRandomValue(),
            'started_at' => now()
        ];
    }
}
