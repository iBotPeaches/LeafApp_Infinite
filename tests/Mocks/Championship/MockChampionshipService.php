<?php
declare(strict_types=1);

namespace Tests\Mocks\Championship;

use Tests\Mocks\BaseMock;

class MockChampionshipService extends BaseMock
{
    public function success(): array
    {
        return [
            'id' => $this->faker->uuid,
            'championship_id' => $this->faker->uuid,
            'name' => 'HCS Open #' . $this->faker->numberBetween(1, 25),
            'region' => $this->faker->randomElement(['na', 'eu']),
            'championship_start' => now()->getPreciseTimestamp()
        ];
    }
}
