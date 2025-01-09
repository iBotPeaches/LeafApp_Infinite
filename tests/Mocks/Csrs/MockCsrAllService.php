<?php

declare(strict_types=1);

namespace Tests\Mocks\Csrs;

use App\Enums\CompetitiveMode;
use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockCsrAllService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag, string $seasonKey = 'CsrSeason2'): array
    {
        return [
            'data' => [
                [
                    'id' => '1',
                    'name' => 'Ranked Arena',
                    'properties' => [
                        'queue' => 'open',
                        'input' => 'crossplay',
                        'experience' => 'arena',
                    ],
                    'response' => [
                        'current' => $this->playlistResponse(),
                        'season' => $this->playlistResponse(),
                        'all_time' => $this->playlistResponse(),
                    ],
                ],
                [
                    'id' => '1',
                    'name' => 'Ranked FFA',
                    'properties' => [
                        'queue' => 'open-queue',
                        'input' => 'crossplay',
                        'experience' => 'arena',
                    ],
                    'response' => [
                        'current' => $this->playlistResponse(),
                        'season' => $this->playlistResponse(),
                        'all_time' => $this->playlistResponse(),
                    ],
                ],
                [
                    'id' => '1',
                    'name' => 'Ranked Arena',
                    'properties' => [
                        'queue' => 'solo-duo',
                        'input' => 'mnk',
                        'experience' => 'arena',
                    ],
                    'response' => [
                        'current' => $this->playlistResponse(),
                        'season' => $this->playlistResponse(),
                        'all_time' => $this->playlistResponse(),
                    ],
                ],
            ],
            'additional' => [
                'params' => [
                    'gamertag' => $gamertag,
                ],
                'query' => [
                    'season_csr' => $seasonKey,
                ],
            ],
        ];
    }

    public function malformed(): array
    {
        return [
            'data' => [
                [
                    'id' => '1',
                    'name' => 'Ranked Arena',
                    'properties' => [
                        'queue' => 'open',
                        'input' => 'crossplay',
                        'experience' => 'Arena',
                    ],
                    'response' => [
                        CompetitiveMode::SEASON => $this->playlistResponse(),
                        'c0rrent' => $this->playlistResponse(),
                    ],
                ],
            ],
            'additional' => [
                'params' => [
                    'gamertag' => '',
                ],
                'query' => [
                    'season_csr' => '',
                ],
            ],
        ];
    }

    private function playlistResponse(): array
    {
        return [
            'value' => $this->faker->numerify('####'),
            'measurement_matches_remaining' => 0,
            'tier' => $this->faker->randomElement(['Gold', 'Diamond', 'Onyx', 'Unranked']),
            'tier_start' => $this->faker->numerify('####'),
            'sub_tier' => $this->faker->numberBetween(1, 5),
            'next_tier' => $this->faker->randomElement(['Diamond', 'Onyx', '']),
            'next_tier_start' => $this->faker->numerify('####'),
            'next_sub_tier' => $this->faker->numberBetween(1, 5),
            'initial_measurement_matches' => 10,
            'tier_image_url' => $this->faker->imageUrl,
            'extra' => [
                [
                    'key' => 'champion',
                    'type' => 'object',
                    'value' => [
                        'rank' => $this->faker->numberBetween(1, 5),
                        'tier_image_url' => $this->faker->imageUrl,
                    ]
                ]
            ],
        ];
    }
}
