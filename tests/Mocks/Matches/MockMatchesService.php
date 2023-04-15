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
                $this->matchBlock(),
                $this->matchBlock(),
            ],
            'additional' => [
                'total' => $count,
                'paging' => [
                    'count' => $count,
                    'offset' => $offset,
                ],
                'params' => [
                    'gamertag' => $gamertag,
                ],
                'query' => [
                    'type' => 'matchmaking',
                    'language' => Language::US,
                ],
            ],
        ];
    }

    public function empty(string $gamertag, int $offset = 0): array
    {
        return [
            'data' => [],
            'additional' => [
                'total' => 0,
                'paging' => [
                    'count' => 0,
                    'offset' => $offset,
                ],
                'params' => [
                    'gamertag' => $gamertag,
                ],
                'query' => [
                    'type' => 'matchmaking',
                    'language' => Language::US,
                ],
            ],
        ];
    }

    private function matchBlock(): array
    {
        return [
            'id' => $this->faker->uuid,
            'details' => [
                'map' => [
                    'id' => $this->faker->uuid,
                    'version' => $this->faker->uuid,
                    'name' => $this->faker->word,
                    'image_urls' => [
                        'hero' => $this->faker->imageUrl,
                        'thumbnail' => $this->faker->imageUrl,
                        'screenshots' => [
                            $this->faker->imageUrl,
                            $this->faker->imageUrl,
                            $this->faker->imageUrl,
                        ],
                    ],
                    'properties' => [
                        'level_id' => '1',
                        'owner_type' => $this->faker->randomElement(['player', 'cms']),
                    ],
                ],
                'ugcgamevariant' => [
                    'id' => $this->faker->uuid,
                    'version' => $this->faker->uuid,
                    'name' => $this->faker->word,
                    'image_urls' => [
                        'hero' => $this->faker->imageUrl,
                        'thumbnail' => $this->faker->imageUrl,
                        'screenshots' => [
                            $this->faker->imageUrl,
                        ],
                    ],
                    'properties' => [
                        'category_id' => 1,
                        'engine_variant_id' => $this->faker->uuid,
                        'owner_type' => 'player',
                    ],
                ],
                'playlist' => [
                    'id' => $this->faker->uuid,
                    'version' => $this->faker->uuid,
                    'name' => $this->faker->word,
                    'image_urls' => [
                        'hero' => $this->faker->imageUrl,
                        'thumbnail' => $this->faker->imageUrl,
                        'screenshots' => [
                            $this->faker->imageUrl,
                        ],
                    ],
                    'attributes' => [
                        'ranked' => true,
                    ],
                    'properties' => [
                        'queue' => 'open',
                        'input' => 'crossplay',
                        'experience' => 'arena',
                    ],
                ],
            ],
            'properties' => [
                'experience' => $this->faker->randomElement(['arena', 'btb']),
                'type' => $this->faker->randomElement(['local', 'custom', 'matchmaking']),
            ],
            'player' => [
                'rank' => $this->faker->numerify('#'),
                'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
                'properties' => [
                    'type' => 'human',
                    'team' => [
                        'id' => $this->faker->numerify('#'),
                        'name' => $this->faker->word,
                    ],
                ],
                'stats' => [
                    'core' => [
                        'summary' => [
                            'kills' => $this->faker->numberBetween(1, 25),
                            'deaths' => $this->faker->numberBetween(1, 25),
                            'assists' => $this->faker->numberBetween(1, 25),
                            'betrayals' => $this->faker->numberBetween(0, 5),
                            'suicides' => $this->faker->numberBetween(0, 5),
                            'spawns' => $this->faker->numberBetween(0, 25),
                            'max_killing_spree' => $this->faker->numberBetween(0, 25),
                            'vehicles' => [
                                'destroys' => $this->faker->numberBetween(0, 2),
                                'hijacks' => $this->faker->numberBetween(0, 2),
                            ],
                            'medals' => [
                                'total' => $this->faker->numberBetween(1, 25),
                                'unique' => $this->faker->numberBetween(0, 5),
                            ],
                        ],
                        'damage' => [
                            'taken' => $this->faker->numerify('####'),
                            'dealt' => $this->faker->numerify('####'),
                        ],
                        'shots' => [
                            'fired' => $this->faker->numerify(),
                            'hit' => $this->faker->numerify(),
                            'missed' => $this->faker->numerify(),
                            'accuracy' => $this->faker->randomFloat(2, 0, 100),
                        ],
                        'rounds' => [
                            'won' => $this->faker->numberBetween(0, 3),
                            'lost' => $this->faker->numberBetween(0, 3),
                            'tied' => $this->faker->numberBetween(0, 3),
                        ],
                        'breakdown' => [
                            'melee' => $this->faker->numberBetween(0, 5),
                            'grenades' => $this->faker->numberBetween(0, 5),
                            'headshots' => $this->faker->numberBetween(0, 5),
                            'power_weapons' => $this->faker->numberBetween(0, 5),
                            'sticks' => $this->faker->numberBetween(0, 5),
                            'assassinations' => $this->faker->numberBetween(0, 5),
                            'vehicles' => [
                                'splatters' => $this->faker->numberBetween(0, 5),
                            ],
                            'miscellaneous' => [
                                'repulsor' => $this->faker->numberBetween(0, 5),
                                'fusion_coils' => $this->faker->numberBetween(0, 5),
                            ],
                        ],
                        'assists' => [
                            'emp' => $this->faker->numberBetween(0, 5),
                            'driver' => $this->faker->numberBetween(0, 5),
                            'callouts' => $this->faker->numberBetween(0, 5),
                        ],
                        'vehicles' => [
                            'destroys' => [
                                [
                                    'value' => 'warthog',
                                    'count' => $this->faker->numberBetween(0, 5),
                                ],
                            ],
                            'hijacks' => [
                                [
                                    'value' => 'warthog',
                                    'count' => $this->faker->numberBetween(0, 5),
                                ],
                            ],
                        ],
                        'medals' => [
                            [
                                'id' => 3233952928,
                                'count' => $this->faker->numberBetween(0, 25),
                            ],
                            [
                                'id' => 1169571763,
                                'count' => $this->faker->numberBetween(0, 25),
                            ],
                        ],
                        'kda' => $this->faker->randomFloat(2, 0, 10),
                        'kdr' => $this->faker->randomFloat(2, 0, 10),
                        'average_life_duration' => [
                            'seconds' => $this->faker->numberBetween(45, 60),
                            'human' => '',
                        ],
                        'scores' => [
                            'personal' => $this->faker->numerify('####'),
                            'points' => 0,
                        ],
                    ],
                    'mode' => [],
                    'mmr' => null,
                ],
                'participation' => [
                    'confirmed' => $this->faker->boolean(),
                    'joined_in_progress' => true,
                    'joined_at' => now()->toIso8601ZuluString(),
                    'left_at' => null,
                    'presence' => [
                        'beginning' => true,
                        'completion' => true,
                    ],
                ],
                'progression' => [
                    'csr' => [
                        'pre_match' => [
                            'value' => $this->faker->numberBetween(-1, 1400),
                            'measurement_matches_remaining' => 0,
                            'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                            'tier_start' => 1350,
                            'sub_tier' => $this->faker->numberBetween(0, 5),
                            'next_tier' => 'Diamond',
                            'next_sub_tier' => 5,
                            'initial_measurement_matches' => 10,
                            'tier_image_url' => $this->faker->imageUrl,
                        ],
                        'post_match' => [
                            'value' => $this->faker->numberBetween(1000, 1400),
                            'measurement_matches_remaining' => 0,
                            'tier' => $this->faker->randomElement(['Diamond', 'Platinum']),
                            'tier_start' => 1200,
                            'sub_tier' => $this->faker->numberBetween(0, 5),
                            'next_tier' => 'Diamond',
                            'next_sub_tier' => 5,
                            'initial_measurement_matches' => 10,
                            'tier_image_url' => $this->faker->imageUrl,
                        ],
                    ],
                ],
                'performances' => [
                    'kills' => [
                        'count' => $this->faker->randomNumber(2),
                        'expected' => $this->faker->numberBetween(50, 55),
                        'standard_deviation' => $this->faker->numberBetween(7, 9),
                    ],
                    'deaths' => [
                        'count' => $this->faker->randomNumber(2),
                        'expected' => $this->faker->numberBetween(50, 55),
                        'standard_deviation' => $this->faker->numberBetween(7, 9),
                    ],
                ],
            ],
            'season' => [
                'id' => 1,
                'version' => 1,
                'properties' => [
                    'identifier' => 'Season1',
                    'csr' => 'CsrSeason1',
                ],
            ],
            'playable_duration' => [
                'seconds' => $this->faker->numerify('####'),
                'human' => '',
            ],
            'started_at' => now()->toIso8601ZuluString(),
            'ended_at' => now()->toIso8601ZuluString(),
        ];
    }
}
