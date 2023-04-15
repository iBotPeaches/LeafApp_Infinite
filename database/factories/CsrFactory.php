<?php

namespace Database\Factories;

use App\Enums\CompetitiveMode;
use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Csr;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Csr> */
class CsrFactory extends Factory
{
    protected $model = Csr::class;

    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'queue' => Queue::getRandomValue(),
            'input' => Input::getRandomValue(),
            'season' => config('services.halodotapi.competitive.season'),
            'mode' => CompetitiveMode::CURRENT,
            'csr' => 1225,
            'matches_remaining' => 0,
            'tier' => 'Diamond',
            'tier_start_csr' => 1450,
            'sub_tier' => 1,
            'next_tier' => 'Diamond',
            'next_sub_tier' => 5,
            'next_csr' => 1500,
        ];
    }
}
