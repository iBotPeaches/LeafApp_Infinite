<?php

declare(strict_types=1);

namespace Tests\Mocks\Matches;

use App\Services\HaloDotApi\Enums\Language;
use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockMatchesService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag, int $count = 2, int $offset = 0): array
    {
        return [
            'data' => [
                [
                    'id' => $this->faker->uuid,
                    'details' => [
                        'gamevariant' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url,
                            ],
                            'properties' => [
                                'category_id' => 1,
                            ],
                        ],
                        'map' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url,
                            ],
                            'properties' => [
                                'level_id' => $this->faker->numberBetween(0, 10),
                            ],
                        ],
                        'playlist' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->imageUrl,
                            ],
                            'properties' => [
                                'queue' => 'open',
                                'input' => 'crossplay',
                                'ranked' => true,
                            ],
                        ],
                    ],
                    'teams' => [
                        'enabled' => $this->faker->boolean,
                        'scoring' => $this->faker->boolean,
                    ],
                    'player' => [
                        'team' => [
                            'id' => $this->faker->numerify('#'),
                            'name' => $this->faker->word,
                        ],
                        'stats' => [
                            'core' => [
                                'summary' => [
                                    'kills' => $this->faker->numberBetween(1, 25),
                                    'deaths' => $this->faker->numberBetween(1, 25),
                                    'assists' => $this->faker->numberBetween(1, 25),
                                    'betrayals' => $this->faker->numberBetween(0, 5),
                                    'suicides' => $this->faker->numberBetween(0, 5),
                                    'vehicles' => [
                                        'destroys' => $this->faker->numberBetween(0, 2),
                                        'hijacks' => $this->faker->numberBetween(0, 2),
                                    ],
                                    'medals' => $this->faker->numberBetween(1, 25),
                                ],
                                'damage' => [
                                    'taken' => $this->faker->numerify('####'),
                                    'dealt' => $this->faker->numerify('####'),
                                ],
                                'shots' => [
                                    'fired' => $this->faker->numerify(),
                                    'landed' => $this->faker->numerify(),
                                    'missed' => $this->faker->numerify(),
                                    'accuracy' => $this->faker->randomFloat(2, 0, 100),
                                ],
                                'kda' => $this->faker->randomFloat(2, 0, 10),
                                'kdr' => $this->faker->randomFloat(2, 0, 10),
                            ],
                        ],
                        'rank' => $this->faker->numerify('#'),
                        'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
                        'progression' => [
                            'csr' => [
                                'pre_match' => [
                                    'value' => $this->faker->numberBetween(-1, 1400),
                                    'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                                    'sub_tier' => $this->faker->numberBetween(0, 5),
                                    'initial_measurement_matches' => 10,
                                    'measurement_matches_remaining' => 0,
                                ],
                                'post_match' => [
                                    'value' => $this->faker->numberBetween(1000, 1400),
                                    'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                                    'tier_start' => 1200,
                                    'sub_tier' => $this->faker->numberBetween(0, 5),
                                    'initial_measurement_matches' => 10,
                                    'measurement_matches_remaining' => 0,
                                ],
                            ],
                        ],
                    ],
                    'experience' => $this->faker->randomElement(['arena', 'btb']),
                    'type' => $this->faker->randomElement(['local', 'custom', 'matchmaking']),
                    'played_at' => now()->toIso8601ZuluString(),
                    'duration' => [
                        'seconds' => $this->faker->numerify('###'),
                        'human' => '',
                    ],
                ],
                [
                    'id' => $this->faker->uuid,
                    'details' => [
                        'gamevariant' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url,
                            ],
                            'properties' => [
                                'category_id' => 1,
                            ],
                        ],
                        'map' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url,
                            ],
                            'properties' => [
                                'level_id' => $this->faker->numberBetween(0, 10),
                            ],
                        ],
                        'playlist' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->imageUrl,
                            ],
                            'properties' => [
                                'queue' => 'open',
                                'input' => 'crossplay',
                                'ranked' => true,
                            ],
                        ],
                    ],
                    'teams' => [
                        'enabled' => $this->faker->boolean,
                        'scoring' => $this->faker->boolean,
                    ],
                    'player' => [
                        'team' => [
                            'id' => $this->faker->numerify('#'),
                            'name' => $this->faker->word,
                        ],
                        'stats' => [
                            'core' => [
                                'summary' => [
                                    'kills' => $this->faker->numberBetween(1, 25),
                                    'deaths' => $this->faker->numberBetween(1, 25),
                                    'assists' => $this->faker->numberBetween(1, 25),
                                    'betrayals' => $this->faker->numberBetween(0, 5),
                                    'suicides' => $this->faker->numberBetween(0, 5),
                                    'vehicles' => [
                                        'destroys' => $this->faker->numberBetween(0, 2),
                                        'hijacks' => $this->faker->numberBetween(0, 2),
                                    ],
                                    'medals' => $this->faker->numberBetween(1, 25),
                                ],
                                'damage' => [
                                    'taken' => $this->faker->numerify('####'),
                                    'dealt' => $this->faker->numerify('####'),
                                ],
                                'shots' => [
                                    'fired' => $this->faker->numerify(),
                                    'landed' => $this->faker->numerify(),
                                    'missed' => $this->faker->numerify(),
                                    'accuracy' => $this->faker->randomFloat(2, 0, 100),
                                ],
                                'kda' => $this->faker->randomFloat(2, 0, 10),
                                'kdr' => $this->faker->randomFloat(2, 0, 10),
                            ],
                        ],
                        'rank' => $this->faker->numerify('#'),
                        'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
                        'progression' => [
                            'csr' => [
                                'pre_match' => [
                                    'value' => $this->faker->numberBetween(-1, 1400),
                                    'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                                    'sub_tier' => $this->faker->numberBetween(0, 5),
                                    'initial_measurement_matches' => 10,
                                    'measurement_matches_remaining' => 0,
                                ],
                                'post_match' => [
                                    'value' => $this->faker->numberBetween(1000, 1400),
                                    'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                                    'tier_start' => 1200,
                                    'sub_tier' => $this->faker->numberBetween(0, 5),
                                    'initial_measurement_matches' => 10,
                                    'measurement_matches_remaining' => 0,
                                ],
                            ],
                        ],
                    ],
                    'experience' => $this->faker->randomElement(['arena', 'btb']),
                    'type' => $this->faker->randomElement(['local', 'custom', 'matchmaking']),
                    'played_at' => now()->toIso8601ZuluString(),
                    'duration' => [
                        'seconds' => $this->faker->numerify('###'),
                        'human' => '',
                    ],
                ],
            ],
            'additional' => [
                'privacy' => [
                    'public' => true,
                ],
                'count' => $count,
                'paging' => [
                    'count' => $count,
                    'offset' => $offset,
                ],
                'parameters' => [
                    'gamertag' => $gamertag,
                    'language' => Language::US,
                    'type' => 'matchmaking',
                ],
            ],
        ];
    }

    public function empty(string $gamertag, int $offset = 0): array
    {
        return [
            'data' => [],
            'additional' => [
                'privacy' => [
                    'public' => true,
                ],
                'count' => 0,
                'paging' => [
                    'count' => 0,
                    'offset' => $offset,
                ],
                'parameters' => [
                    'gamertag' => $gamertag,
                    'language' => Language::US,
                    'type' => 'matchmaking',
                ],
            ],
        ];
    }
}
