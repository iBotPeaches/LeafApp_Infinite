<?php

namespace Database\Factories;

use App\Models\Map;
use App\Models\Overview;
use App\Models\OverviewMap;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OverviewMap> */
class OverviewMapFactory extends Factory
{
    public function definition(): array
    {
        return [
            'overview_id' => Overview::factory(),
            'map_id' => Map::factory(),
            'released_at' => now(),
        ];
    }
}
