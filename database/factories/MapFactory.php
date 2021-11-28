<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Map[]|Map create($attributes = [], ?Map $parent = null)
 * @method Collection|Map[] createMany(iterable $records)
 * @method Map createOne($attributes = [])
 * @method Collection|Map[]|Map make($attributes = [], ?Map $parent = null)
 * @method Map makeOne($attributes = [])
 */
class MapFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'version' => $this->faker->unique()->uuid,
            'name' => $this->faker->word,
            'thumbnail_url' => $this->faker->imageUrl,
        ];
    }
}
