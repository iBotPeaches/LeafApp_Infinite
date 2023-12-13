<?php

declare(strict_types=1);

namespace Tests\Mocks\Championship;

use Tests\Mocks\BaseMock;

class MockChampionshipService extends BaseMock
{
    public function success(?string $championshipId = null): array
    {
        $championshipId ??= $this->faker->uuid;

        return [
            'id' => $championshipId,
            'championship_id' => $championshipId,
            'name' => 'HCS Open #'.$this->faker->numberBetween(1, 25),
            'type' => $this->faker->randomElement(['roundRobin', 'doubleElimination', 'stage']),
            'status' => $this->faker->randomElement(['started', 'finished']),
            'region' => $this->faker->randomElement(['na', 'eu']),
            'championship_start' => now()->getTimestampMs(),
            'description' => $this->faker->paragraph,
        ];
    }
}
