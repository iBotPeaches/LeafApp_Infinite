<?php

declare(strict_types=1);

namespace Tests\Mocks\Matches;

use App\Services\HaloDotApi\Enums\Language;
use App\Services\HaloDotApi\Enums\PlayerType;
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
                [
                    'id' => $matchId,
                    'match' => [
                        'id' => $matchId,
                        'details' => [
                            'gamevariant' => [
                                'name' => $randomCategoryName,
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
                                    'level_id' => 1,
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
                            'details' => [
                                [
                                    'team' => [
                                        'id' => 0,
                                        'name' => 'Eagle',
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
                                            'kda' => $this->faker->randomFloat(2, 0, 10),
                                            'kdr' => $this->faker->randomFloat(2, 0, 10),
                                            'scores' => [
                                                'personal' => $this->faker->numerify('######'),
                                                'points' => $this->faker->numerify('######'),
                                            ],
                                        ],
                                        'mode' => $this->getMode($randomCategoryName),
                                        'mmr' => $this->faker->numerify('####'),
                                    ],
                                    'rank' => 1,
                                    'outcome' => 'win',
                                    'odds' => [
                                        'winning' => 50,
                                        'losing' => 49,
                                    ],
                                ],
                                [
                                    'team' => [
                                        'id' => 1,
                                        'name' => 'Cobra',
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
                                            'kda' => $this->faker->randomFloat(2, 0, 10),
                                            'kdr' => $this->faker->randomFloat(2, 0, 10),
                                            'scores' => [
                                                'personal' => $this->faker->numerify('######'),
                                                'points' => $this->faker->numerify('######'),
                                            ],
                                        ],
                                        'mode' => $this->getMode($randomCategoryName),
                                        'mmr' => $this->faker->numerify('####'),
                                    ],
                                    'rank' => 1,
                                    'outcome' => 'loss',
                                    'odds' => [
                                        'winning' => 49,
                                        'losing' => 50,
                                    ],
                                ],
                            ],
                        ],
                        'players' => [
                            $this->playerBlock($randomCategoryName, $gamertag1),
                            $this->playerBlock($randomCategoryName, $gamertag2),
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
            ],
            'additional' => [
                'count' => 1,
                'parameters' => [
                    'language' => Language::US,
                    'ids' => [
                        $this->faker->uuid(),
                    ],
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

    private function playerBlock(string $categoryName, string $gamertag): array
    {
        return [
            'details' => [
                'name' => $gamertag,
                'type' => PlayerType::PLAYER,
                'resolved' => true,
            ],
            'team' => [
                'id' => $this->faker->randomNumber(1),
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
                    'kda' => $this->faker->randomFloat(2, 0, 10),
                    'kdr' => $this->faker->randomFloat(2, 0, 10),
                    'scores' => [
                        'personal' => $this->faker->numerify('######'),
                        'points' => $this->faker->numerify('######'),
                    ],
                ],
                'mode' => $this->getMode($categoryName),
                'mmr' => $this->faker->numerify('####'),
            ],
            'rank' => $this->faker->randomNumber(1),
            'outcome' => $this->faker->randomElement(['win', 'loss', 'draw']),
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
