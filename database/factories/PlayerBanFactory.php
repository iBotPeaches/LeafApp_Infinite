<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Player;
use App\Models\PlayerBan;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PlayerBan> */
class PlayerBanFactory extends Factory
{
    protected $model = PlayerBan::class;

    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'key' => md5($this->faker->word),
            'message' => $this->faker->sentence,
            'ends_at' => now()->addYear()->toIso8601ZuluString(),
            'type' => $this->faker->word,
            'scope' => $this->faker->word,
        ];
    }

    public function expired(): self
    {
        return $this->state(fn () => [
            'ends_at' => now()->subDay()->toIso8601ZuluString(),
        ]);
    }
}
