<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Analytic> */
class AnalyticFactory extends Factory
{
    protected $model = Analytic::class;

    public function definition(): array
    {
        return [
            'key' => AnalyticKey::MOST_MEDALS_SR->value,
            'game_id' => Game::factory(),
            'player_id' => Player::factory(),
            'value' => $this->faker->randomFloat(2, 0, 20),
        ];
    }
}
