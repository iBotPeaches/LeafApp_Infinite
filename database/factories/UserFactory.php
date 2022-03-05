<?php
declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<User> */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'google_id' => $this->faker->numerify('#############'),
            'player_id' => Player::factory()
        ];
    }
}
