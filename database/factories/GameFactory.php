<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Experience;
use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Category;
use App\Models\Game;
use App\Models\Map;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Game[]|Game create($attributes = [], ?Game $parent = null)
 * @method Collection|Game[] createMany(iterable $records)
 * @method Game createOne($attributes = [])
 * @method Collection|Game[]|Game make($attributes = [], ?Game $parent = null)
 * @method Game makeOne($attributes = [])
 */
class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'category_id' => Category::factory(),
            'map_id' => Map::factory(),
            'playlist_id' => Playlist::factory(),
            'is_ffa' => $this->faker->boolean,
            'is_scored' => $this->faker->boolean,
            'experience' => Experience::getRandomValue(),
            'occurred_at' => Carbon::now(),
            'duration_seconds' => $this->faker->numerify('####'),
        ];
    }
}
