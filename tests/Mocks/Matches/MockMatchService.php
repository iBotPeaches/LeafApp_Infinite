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
        $randomCategoryName = $this->faker->randomElement(['CTF', 'Strongholds', 'Slayer', 'Oddball']);

        return [
            'data' => [
                'id' => $this->faker->uuid,
                'details' => [
                    'category' => [
                        'name' => $randomCategoryName,
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
                                ],
                                'mode' => $this->getMode($randomCategoryName)
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
                                    'score' => $this->faker->numerify('####'),
                                    'points' => $this->faker->numberBetween(0, 50),
                                ],
                                'mode' => $this->getMode($randomCategoryName),
                            ],
                            'rank' => 2,
                            'outcome' => 'loss'
                        ]
                    ]
                ],
                'players' => [
                    $this->playerBlock($randomCategoryName, $gamertag1),
                    $this->playerBlock($randomCategoryName, $gamertag2),
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

    private function getMode(string $mode): ?array
    {
        switch ($mode) {
            case 'Oddball':
                return $this->modeOddball();

            case 'CTF':
                return $this->modeFlag();

            case 'Strongholds':
                return $this->modeStrongholds();

            case 'Slayer':
            default:
                return $this->modeSlayer();
        }
    }

    private function modeStrongholds(): array
    {
        return [
            'zones' => [
                'secured' => $this->faker->numberBetween(10, 25),
                'captured' => $this->faker->numberBetween(0, 25),
                'occupation' => [
                    'ticks' => $this->faker->numerify('##'),
                    'duration' => [
                        'seconds' => $this->faker->numberBetween(1, 50),
                        'human' => ''
                    ],
                ],
                'kills' => [
                    'defensive' => $this->faker->numberBetween(0, 40),
                    'offensive' => $this->faker->numberBetween(0, 40)
                ]
            ]
        ];
    }

    private function modeOddball(): array
    {
        return [
            'oddballs' => [
                'grabs' => $this->faker->numberBetween(0, 5),
                'controls' => $this->faker->numberBetween(0, 5),
                'possession' => [
                    'ticks' => $this->faker->numerify('##'),
                    'longest' => [
                        'seconds' => $this->faker->numberBetween(1, 50),
                        'human' => ''
                    ],
                    'total' => [
                        'seconds' => $this->faker->numberBetween(1, 50),
                        'human' => ''
                    ]
                ],
                'kills' => [
                    'carriers' => $this->faker->numberBetween(0, 5),
                    'as' => [
                        'carrier' => $this->faker->numberBetween(0, 5)
                    ]
                ]
            ]
        ];
    }

    private function modeSlayer(): ?array
    {
        return null;
    }

    private function modeFlag(): array
    {
        return [
            'flags' => [
                'grabs' => $this->faker->numberBetween(1, 10),
                'steals' => $this->faker->numberBetween(1, 10),
                'secures' => $this->faker->numberBetween(1, 10),
                'returns' => $this->faker->numberBetween(1, 10),
                'possession' => [
                    'duration' => [
                        'seconds' => $this->faker->numberBetween(1, 50),
                        'human' => ''
                    ]
                ],
                'captures' => [
                    'total' => $this->faker->numberBetween(1, 10),
                    'assists' => $this->faker->numberBetween(1, 10),
                ],
                'kills' => [
                    'carriers' => $this->faker->numberBetween(0, 5),
                    'returners' => $this->faker->numberBetween(0, 5),
                    'as' => [
                        'carrier' => $this->faker->numberBetween(0, 1),
                        'returner' => $this->faker->numberBetween(0, 1),
                    ]
                ]
            ]
        ];
    }

    private function playerBlock(string $categoryName, string $gamertag): array
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
                        ],
                        'medals' => [
                            [
                                'id' => 3233952928,
                                'count' => $this->faker->numberBetween(0, 25),
                            ],
                            [
                                'id' => 1169571763,
                                'count' => $this->faker->numberBetween(0, 25),
                            ]
                        ],
                    ],
                    'kda' => $this->faker->randomFloat(2, 0, 10),
                    'kdr' => $this->faker->randomFloat(2, 0, 10),
                    'score' => $this->faker->numerify('####')
                ],
                'mode' => $this->getMode($categoryName),
            ],
            'rank' => $this->faker->numerify('#'),
            'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
            'participation' => [
                'joined_in_progress' => true,
                'presence' => [
                    'beginning' => true,
                    'completion' => true
                ]
            ],
            'progression' => [
                'csr' => [
                    'pre_match' => [
                        'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                        'value' => $this->faker->numberBetween(-1, 1400),
                        'tier_start' => 1200,
                        'sub_tier' => $this->faker->numberBetween(0, 5),
                        'tier_image_url' => $this->faker->imageUrl,
                    ],
                    'post_match' => [
                        'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                        'value' => $this->faker->numberBetween(1000, 1400),
                        'tier_start' => 1200,
                        'sub_tier' => $this->faker->numberBetween(0, 5),
                        'tier_image_url' => $this->faker->imageUrl,
                    ]
                ]
            ]
        ];
    }
}
