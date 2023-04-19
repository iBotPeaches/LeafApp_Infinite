<?php

declare(strict_types=1);

namespace Tests\Mocks\BanSummary;

use Tests\Mocks\BaseMock;

class MockBanSummaryService extends BaseMock
{
    public function banned(string $gamertag = ''): array
    {
        return [
            'data' => [
                [
                    'message' => $this->faker->sentence,
                    'end_date' => now()->addYear()->toIso8601ZuluString(),
                    'properties' => [
                        'type' => 'matchmaking',
                        'scope' => 'global',
                    ],
                ],
            ],
            'additional' => [
                'total' => 1,
                'params' => [
                    'gamertag' => $gamertag,
                ],
                'query' => [
                    'language' => 'en-US',
                ],
            ],
        ];
    }

    public function unbanned(string $gamertag = ''): array
    {
        return [
            'data' => [],
            'additional' => [
                'total' => 0,
                'params' => [
                    'gamertag' => $gamertag,
                ],
                'query' => [
                    'language' => 'en-US',
                ],
            ],
        ];
    }
}
