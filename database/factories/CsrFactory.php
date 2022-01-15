<?php

namespace Database\Factories;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Csr;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @method Collection|Csr[]|Csr create($attributes = [], ?Csr $parent = null)
 * @method Collection|Csr[] createMany(iterable $records)
 * @method Csr createOne($attributes = [])
 * @method Collection|Csr[]|Csr make($attributes = [], ?Csr $parent = null)
 * @method Csr makeOne($attributes = [])
 */
class CsrFactory extends Factory
{
    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'queue' => Queue::getRandomValue(),
            'input' => Input::getRandomValue(),
            'season' => 1,
            'csr' => 1225,
            'matches_remaining' => 0,
            'tier' => 'Diamond',
            'tier_start_csr' => 1450,
            'sub_tier' => 1,
            'next_tier' => 'Diamond',
            'next_sub_tier' => 5,
            'next_csr' => 1500,
            'season_tier' => 'Diamond',
            'season_sub_tier' => 4,
        ];
    }
}
