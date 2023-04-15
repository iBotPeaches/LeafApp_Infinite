<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Season> */
class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition(): array
    {
        return [
            'key' => '1-2',
            'identifier' => 'Season1-2',
            'csr_key' => 'CsrSeason1-2',
            'season_id' => 1,
            'season_version' => 2,
            'name' => 'Heroes of Reach',
            'description' => 'Because of you, we found Halo, unlocked its secrets, shattered our enemy\'s resolve.',
        ];
    }
}
