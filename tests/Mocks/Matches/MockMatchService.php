<?php
declare(strict_types=1);

namespace Tests\Mocks\Matches;

use App\Services\Autocode\Enums\PlayerType;
use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockMatchService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag1, string $gamertag2): array
    {
        return [
            'data' => [
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
                        'queue' => 'open',
                        'input' => 'crossplay',
                        'ranked' => true,
                        'asset' => [
                            'id' => $this->faker->uuid,
                            'version' => $this->faker->uuid
                        ]
                    ]
                ],
                'teams' => [
                    'enabled' => $this->faker->boolean,
                    'scoring' => $this->faker->boolean,
                    'details' => [
                        [
                            'team' => [
                                'id' => 0,
                                'name' => 'Eagle',
                                'emblem_url' => $this->faker->imageUrl
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
                                ]
                            ],
                            'rank' => 1,
                            'outcome' => 'win'
                        ],
                        [
                            'team' => [
                                'id' => 1,
                                'name' => 'Cobra',
                                'emblem_url' => $this->faker->imageUrl
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
                                ]
                            ],
                            'rank' => 2,
                            'outcome' => 'loss'
                        ]
                    ]
                ],
                'players' => [
                    $this->playerBlock($gamertag1),
                    $this->playerBlock($gamertag2),
                ],
                'experience' => $this->faker->randomElement(['arena', 'btb']),
                'played_at' => now()->toIso8601ZuluString(),
                'duration' => [
                    'seconds' => $this->faker->numerify('###'),
                    'human' => ''
                ]
            ],
        ];
    }

    private function playerBlock(string $gamertag): array
    {
        return [
            'gamertag' => $gamertag,
            'type' => PlayerType::PLAYER,
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
            ],
            'rank' => $this->faker->numerify('#'),
            'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
        ];
    }
}
