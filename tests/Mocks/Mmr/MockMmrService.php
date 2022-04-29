<?php
declare(strict_types=1);

namespace Tests\Mocks\Mmr;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockMmrService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag): array
    {
        return [
            'data' => [
                'value' => $this->faker->numberBetween(0, 2000),
                'match' => [
                    'id' => $this->faker->uuid
                ]
            ],
            'additional' => [
                'gamertag' => $gamertag
            ]
        ];
    }

    public function empty(string $gamertag): array
    {
        return [
            'data' => [
                'value' => null,
                'match' => [
                    'id' => null
                ]
            ],
            'additional' => [
                'gamertag' => $gamertag
            ]
        ];
    }
}
