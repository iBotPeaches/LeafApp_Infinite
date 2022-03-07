<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Playlist;
use App\Models\Scrim;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Scrim> */
class ScrimFactory extends Factory
{
    protected $model = Scrim::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'is_complete' => true,
            'status_message' => $this->faker->word,
        ];
    }
}
