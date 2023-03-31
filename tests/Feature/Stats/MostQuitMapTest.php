<?php

declare(strict_types=1);

namespace Tests\Feature\Stats;

use App\Enums\Outcome as O;
use App\Models\Category;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Map;
use App\Support\Analytics\Stats\MostQuitMap;
use Tests\TestCase;

class MostQuitMapTest extends TestCase
{
    private const LAST_SPARTAN_STANDING_CATEGORY_UUID = '3fdb396febedc607ddd3416aea2ff5a3';

    /** @dataProvider fixtureDataProvider */
    public function testCalculatesCorrectly(array $expected, array $fixture): void
    {
        // Arrange
        foreach ($fixture as $mapAndGames) {
            $map = Map::factory()->createOne([
                'name' => $mapAndGames['mapName'],
            ]);

            foreach ($mapAndGames['games'] as $categoryAndPlayers) {
                $category = isset($categoryAndPlayers['categoryUuid'])
                    ? Category::factory()->createOne([
                        'uuid' => $categoryAndPlayers['categoryUuid'],
                    ])
                    : Category::factory()->createOne();

                $game = Game::factory()
                    ->for($map)
                    ->for($category)
                    ->createOne();

                GamePlayer::factory()
                    ->for($game)
                    ->sequence(...array_map(
                        static fn ($outcome): array => ['outcome' => $outcome],
                        $categoryAndPlayers['gamePlayers']
                    ))
                    ->count(count($categoryAndPlayers['gamePlayers']))
                    ->create();
            }
        }

        $sut = new MostQuitMap();

        // Act
        $results = $sut->results();

        // Assert
        $this->assertEquals(
            $expected,
            $results->map->only(['name', 'percent_quit'])->values()->toArray()
        );
    }

    public static function fixtureDataProvider(): array
    {
        return [
            [
                [
                    [
                        'name' => 'Map 3',
                        'percent_quit' => 100,
                    ],
                    [
                        'name' => 'Map 8',
                        'percent_quit' => 90,
                    ],
                    [
                        'name' => 'Map 7',
                        'percent_quit' => 75,
                    ],
                    [
                        'name' => 'Map 12',
                        'percent_quit' => 66.6667,
                    ],
                    [
                        'name' => 'Map 11',
                        'percent_quit' => 57.1429,
                    ],
                    [
                        'name' => 'A Map 10',
                        'percent_quit' => 50,
                    ],
                    [
                        'name' => 'Map 1',
                        'percent_quit' => 50,
                    ],
                    [
                        'name' => 'Z Map 9',
                        'percent_quit' => 50,
                    ],
                    [
                        'name' => 'Map 4',
                        'percent_quit' => 25,
                    ],
                    [
                        'name' => 'Map 6',
                        'percent_quit' => 21.8750,
                    ],
                ],
                [
                    [
                        'mapName' => 'Map 1',
                        'games' => [
                            [
                                'categoryUuid' => self::LAST_SPARTAN_STANDING_CATEGORY_UUID,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::LEFT],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 2',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 3',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 4',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::WIN, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 5',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 6',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LOSS, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LOSS, O::LOSS, O::LOSS, O::LOSS, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LOSS, O::LOSS, O::LOSS, O::LOSS, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 7',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 8',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LOSS, O::LEFT, O::LEFT, O::LEFT, O::LEFT],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::LEFT],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Z Map 9',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'A Map 10',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 11',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::WIN, O::WIN],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT, O::LEFT, O::LEFT, O::WIN, O::WIN, O::WIN, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 12',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::WIN, O::LOSS],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::LEFT],
                            ],
                        ],
                    ],
                ],
            ],
            [
                [
                    [
                        'name' => 'Map 1',
                        'percent_quit' => 50,
                    ],
                ],
                [
                    [
                        'mapName' => 'Map 1',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::LEFT, O::WIN],
                            ],
                        ],
                    ],
                    [
                        'mapName' => 'Map 2',
                        'games' => [
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::WIN, O::LOSS],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::WIN, O::LOSS],
                            ],
                            [
                                'categoryUuid' => null,
                                'gamePlayers' => [O::WIN, O::LOSS],
                            ],
                        ],
                    ],

                ],
            ],
        ];
    }
}
