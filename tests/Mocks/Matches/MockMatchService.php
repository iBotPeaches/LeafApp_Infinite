<?php

declare(strict_types=1);

namespace Tests\Mocks\Matches;

use App\Services\DotApi\Enums\Language;
use App\Services\DotApi\Enums\PlayerType;
use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockMatchService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag1, string $gamertag2): array
    {
        $randomCategoryName = $this->faker->randomElement(['CTF', 'Strongholds', 'Slayer', 'Oddball', 'Attrition']);
        $matchId = $this->faker->uuid;

        return [
            'data' => [
                'id' => $matchId,
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
                'teams' => [
                    $this->teamBlock(0, 'Eagle', $randomCategoryName),
                    $this->teamBlock(1, 'Cobra', $randomCategoryName),
                ],
                'players' => [
                    $this->playerBlock($randomCategoryName, $gamertag1),
                    $this->playerBlock($randomCategoryName, $gamertag2),
                ],
                'experience' => $this->faker->randomElement(['arena', 'btb']),
                'type' => $this->faker->randomElement(['local', 'custom', 'matchmaking']),
                'started_at' => now()->toIso8601ZuluString(),
                'ended_at' => now()->addMinute()->toIso8601ZuluString(),
                'playable_duration' => [
                    'seconds' => $this->faker->numerify('###'),
                    'human' => '',
                ],
            ],
            'additional' => [
                'query' => [
                    'language' => Language::US,
                ],
            ],
        ];
    }

    private function getMode(string $mode): ?array
    {
        return match ($mode) {
            'Oddball' => $this->modeOddball(),
            'CTF' => $this->modeFlag(),
            'Strongholds' => $this->modeStrongholds(),
            'Attrition' => $this->modeAttrition(),
            default => $this->modeSlayer(),
        };
    }

    private function modeAttrition(): array
    {
        return [
            'allies_revived' => $this->faker->numberBetween(1, 10),
            'elimination_assists' => $this->faker->numberBetween(1, 10),
            'elimination_order' => $this->faker->numberBetween(1, 10),
            'eliminations' => $this->faker->numberBetween(1, 10),
            'enemy_revives_denied' => $this->faker->numberBetween(1, 10),
            'executions' => $this->faker->numberBetween(1, 10),
            'kills_as_last_player_standing' => $this->faker->numberBetween(1, 10),
            'last_players_standing_killed' => $this->faker->numberBetween(1, 10),
            'lives_remaining' => null,
            'rounds_survived' => $this->faker->numberBetween(1, 10),
            'times_revived_by_ally' => $this->faker->numberBetween(1, 10),
        ];
    }

    private function modeStrongholds(): array
    {
        return [
            'total_zone_occupation_time' => [
                'seconds' => $this->faker->numberBetween(1, 10),
                'human' => '',
            ],
            'zone_captures' => $this->faker->numberBetween(1, 10),
            'zone_defensive_kills' => $this->faker->numberBetween(1, 10),
            'zone_offensive_kills' => $this->faker->numberBetween(1, 10),
            'zone_scoring_ticks' => $this->faker->numberBetween(1, 10),
            'zone_secures' => $this->faker->numberBetween(1, 10),
        ];
    }

    private function modeOddball(): array
    {
        return [
            'kills_as_skull_carrier' => $this->faker->numberBetween(1, 10),
            'longest_time_as_skull_carrier' => [
                'seconds' => $this->faker->numberBetween(1, 10),
                'human' => '',
            ],
            'skull_carriers_killed' => $this->faker->numberBetween(1, 10),
            'skull_grabs' => $this->faker->numberBetween(1, 10),
            'skull_scoring_ticks' => $this->faker->numberBetween(1, 10),
            'time_as_skull_carrier' => [
                'seconds' => $this->faker->numberBetween(1, 10),
                'human' => '',
            ],
        ];
    }

    private function modeSlayer(): ?array
    {
        return null;
    }

    private function modeFlag(): array
    {
        return [
            'flag_capture_assists' => $this->faker->numberBetween(1, 10),
            'flag_captures' => $this->faker->numberBetween(1, 10),
            'flag_carriers_killed' => $this->faker->numberBetween(1, 10),
            'flag_grabs' => $this->faker->numberBetween(1, 10),
            'flag_returners_killed' => $this->faker->numberBetween(1, 10),
            'flag_returns' => $this->faker->numberBetween(1, 10),
            'flag_secures' => $this->faker->numberBetween(1, 10),
            'flag_steals' => $this->faker->numberBetween(1, 10),
            'kills_as_flag_carrier' => $this->faker->numberBetween(1, 10),
            'kills_as_flag_returner' => $this->faker->numberBetween(1, 10),
            'time_as_flag_carrier' => [
                'seconds' => $this->faker->numberBetween(1, 10),
                'human' => '',
            ],
        ];
    }

    private function teamBlock(int $internalTeamId, string $teamName, string $categoryName): array
    {
        return [
            'id' => $internalTeamId,
            'name' => $teamName,
            'rank' => 1,
            'outcome' => 'win',
            'stats' => [
                'core' => [
                    'summary' => [
                        'kills' => $this->faker->numberBetween(1, 25),
                        'deaths' => $this->faker->numberBetween(1, 25),
                        'assists' => $this->faker->numberBetween(1, 25),
                        'betrayals' => $this->faker->numberBetween(0, 5),
                        'suicides' => $this->faker->numberBetween(0, 5),
                        'spawns' => $this->faker->numberBetween(0, 25),
                        'max_killing_spree' => $this->faker->numberBetween(0, 5),
                        'vehicles' => [
                            'destroys' => $this->faker->numberBetween(0, 2),
                            'hijacks' => $this->faker->numberBetween(0, 2),
                        ],
                        'medals' => [
                            'total' => $this->faker->numberBetween(1, 25),
                            'unique' => $this->faker->numberBetween(1, 5),
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
                        'won' => $this->faker->numberBetween(0, 2),
                        'lost' => $this->faker->numberBetween(0, 2),
                        'tied' => $this->faker->numberBetween(0, 2),
                    ],
                    'breakdown' => [
                        'kills' => [
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
                    ],
                    'kdr' => $this->faker->randomFloat(2, 0, 10),
                    'kda' => $this->faker->randomFloat(2, 0, 10),
                    'average_life_duration' => [
                        'seconds' => $this->faker->numberBetween(45, 60),
                        'human' => '',
                    ],
                    'scores' => [
                        'personal' => $this->faker->numerify('######'),
                        'points' => $this->faker->numerify('######'),
                    ],
                ],
                'mode' => $this->getMode($categoryName),
                'mmr' => $this->faker->numerify('####'),
            ],
            'odds' => [
                'winning' => 50,
                'losing' => 49,
            ],
        ];
    }

    private function playerBlock(string $categoryName, string $gamertag): array
    {
        return [
            'name' => $gamertag,
            'rank' => $this->faker->randomNumber(1),
            'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
            'attributes' => [
                'resolved' => true,
            ],
            'properties' => [
                'type' => PlayerType::PLAYER,
                'team' => [
                    'id' => $this->faker->randomNumber(1),
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
                        'max_killing_spree' => $this->faker->numberBetween(0, 5),
                        'vehicles' => [
                            'destroys' => $this->faker->numberBetween(0, 2),
                            'hijacks' => $this->faker->numberBetween(0, 2),
                        ],
                        'medals' => [
                            'total' => $this->faker->numberBetween(1, 25),
                            'unique' => $this->faker->numberBetween(1, 5),
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
                        'won' => $this->faker->numberBetween(0, 2),
                        'lost' => $this->faker->numberBetween(0, 2),
                        'tied' => $this->faker->numberBetween(0, 2),
                    ],
                    'breakdown' => [
                        'kills' => [
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
                    ],
                    'kdr' => $this->faker->randomFloat(2, 0, 10),
                    'kda' => $this->faker->randomFloat(2, 0, 10),
                    'average_life_duration' => [
                        'seconds' => $this->faker->numberBetween(45, 60),
                        'human' => '',
                    ],
                    'scores' => [
                        'personal' => $this->faker->numerify('######'),
                        'points' => $this->faker->numerify('######'),
                    ],
                ],
                'mode' => $this->getMode($categoryName),
                'mmr' => $this->faker->numerify('####'),
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
                        'tier_start' => 1150,
                        'sub_tier' => $this->faker->numberBetween(0, 5),
                        'next_tier' => 'Diamond',
                        'next_sub_tier' => 1,
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
                        'next_sub_tier' => 1,
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
        ];
    }
}
