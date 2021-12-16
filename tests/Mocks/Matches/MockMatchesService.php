<?php
declare(strict_types=1);

namespace Tests\Mocks\Matches;

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
                        'category' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url
                            ]
                        ],
                        'map' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url
                            ]
                        ],
                        'playlist' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->imageUrl
                            ],
                            'properties' => [
                                'queue' => 'open',
                                'input' => 'crossplay',
                                'ranked' => true,
                            ]
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
                            'emblem_url' => $this->faker->url
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
                                    'medals' => $this->faker->numberBetween(1, 25)
                                ],
                                'damage' => [
                                    'taken' => $this->faker->numerify('####'),
                                    'dealt' => $this->faker->numerify('####')
                                ],
                                'shots' => [
                                    'fired' => $this->faker->numerify(),
                                    'landed' => $this->faker->numerify(),
                                    'missed' => $this->faker->numerify(),
                                    'accuracy' => $this->faker->randomFloat(2, 0, 100)
                                ],
                                'rounds' => [
                                    'won' => $this->faker->numberBetween(0, 2),
                                    'lost' => $this->faker->numberBetween(0, 2),
                                    'tied' => $this->faker->numberBetween(0, 2),
                                ],
                                'breakdowns' => [
                                    'kills' => [
                                        'melee' => $this->faker->numberBetween(0, 5),
                                        'grenades' => $this->faker->numberBetween(0, 5),
                                        'headshots' => $this->faker->numberBetween(0, 5),
                                        'power_weapons' => $this->faker->numberBetween(0, 5),
                                    ],
                                    'assists' => [
                                        'emp' => $this->faker->numberBetween(0, 5),
                                        'driver' => $this->faker->numberBetween(0, 5),
                                        'callouts' => $this->faker->numberBetween(0, 5)
                                    ]
                                ],
                                'kda' => $this->faker->randomFloat(2, 0, 10),
                                'kdr' => $this->faker->randomFloat(2, 0, 10),
                                'score' => $this->faker->numerify('####')
                            ],
                            'mode' => null,
                        ],
                        'rank' => $this->faker->numerify('#'),
                        'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
                        'participation' => [
                            'joined_in_progress' => true,
                            'presence' => [
                                'beginning' => true,
                                'completion' => true
                            ]
                        ]
                    ],
                    'experience' => $this->faker->randomElement(['arena', 'btb']),
                    'ranked' => $this->faker->boolean,
                    'played_at' => now()->toIso8601ZuluString(),
                    'duration' => [
                        'seconds' => $this->faker->numerify('###'),
                        'human' => ''
                    ]
                ],
                [
                    'id' => $this->faker->uuid,
                    'details' => [
                        'category' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url
                            ]
                        ],
                        'map' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->url
                            ]
                        ],
                        'playlist' => [
                            'name' => $this->faker->word,
                            'asset' => [
                                'id' => $this->faker->uuid,
                                'version' => $this->faker->uuid,
                                'thumbnail_url' => $this->faker->imageUrl
                            ],
                            'properties' => [
                                'queue' => 'solo_duo',
                                'input' => 'controller',
                                'ranked' => true,
                            ]
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
                            'emblem_url' => $this->faker->url
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
                                    'medals' => $this->faker->numberBetween(1, 25)
                                ],
                                'damage' => [
                                    'taken' => $this->faker->numerify('####'),
                                    'dealt' => $this->faker->numerify('####')
                                ],
                                'shots' => [
                                    'fired' => $this->faker->numerify(),
                                    'landed' => $this->faker->numerify(),
                                    'missed' => $this->faker->numerify(),
                                    'accuracy' => $this->faker->randomFloat(2, 0, 100)
                                ],
                                'rounds' => [
                                    'won' => $this->faker->numberBetween(0, 2),
                                    'lost' => $this->faker->numberBetween(0, 2),
                                    'tied' => $this->faker->numberBetween(0, 2),
                                ],
                                'breakdowns' => [
                                    'kills' => [
                                        'melee' => $this->faker->numberBetween(0, 5),
                                        'grenades' => $this->faker->numberBetween(0, 5),
                                        'headshots' => $this->faker->numberBetween(0, 5),
                                        'power_weapons' => $this->faker->numberBetween(0, 5),
                                    ],
                                    'assists' => [
                                        'emp' => $this->faker->numberBetween(0, 5),
                                        'driver' => $this->faker->numberBetween(0, 5),
                                        'callouts' => $this->faker->numberBetween(0, 5)
                                    ]
                                ],
                                'kda' => $this->faker->randomFloat(2, 0, 10),
                                'kdr' => $this->faker->randomFloat(2, 0, 10),
                                'score' => $this->faker->numerify('####')
                            ],
                            'mode' => null,
                        ],
                        'rank' => $this->faker->numerify('#'),
                        'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
                        'participation' => [
                            'joined_in_progress' => true,
                            'presence' => [
                                'beginning' => true,
                                'completion' => true
                            ]
                        ]
                    ],
                    'experience' => $this->faker->randomElement(['arena', 'btb']),
                    'ranked' => $this->faker->boolean,
                    'played_at' => now()->toIso8601ZuluString(),
                    'duration' => [
                        'seconds' => $this->faker->numerify('###'),
                        'human' => ''
                    ]
                ]
            ],
            'count' => $count,
            'paging' => [
                'count' => $count,
                'offset' => $offset
            ],
            'additional' => [
                'gamertag' => $gamertag,
                'mode' => 'matchmade'
            ]
        ];
    }

    public function empty(string $gamertag, int $offset = 0): array
    {
        return [
            'data' => [],
            'count' => 0,
            'paging' => [
                'count' => 0,
                'offset' => $offset
            ],
            'additional' => [
                'gamertag' => $gamertag,
                'mode' => 'matchmade'
            ]
        ];
    }
}
