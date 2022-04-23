<?php
declare(strict_types=1);

namespace Tests\Mocks\Mmr;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockMmrService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag, int $season = 1): array
    {
        return [
            'data' => [
                'value' => $this->faker->numberBetween(0, 2000),
                'match' => [
                    'id' => $this->faker->uuid
                ]
            ],
            'additional' => [
                'gamertag' => $gamertag,
                'season' => $season
            ]
        ];
    }
}
