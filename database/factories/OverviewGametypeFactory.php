<?php

namespace Database\Factories;

use App\Enums\BaseGametype;
use App\Models\Overview;
use App\Models\OverviewGametype;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OverviewGametype> */
class OverviewGametypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'overview_id' => Overview::factory(),
            'gametype' => (int) BaseGametype::getRandomValue(),
            'name' => BaseGametype::getRandomKey(),
            'gamevariant_ids' => [],
        ];
    }
}
