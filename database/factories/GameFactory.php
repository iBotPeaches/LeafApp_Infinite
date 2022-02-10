<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Experience;
use App\Models\Category;
use App\Models\Game;
use App\Models\Map;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Game> */
class GameFactory extends Factory
{
    protected $model = Game::class;

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
