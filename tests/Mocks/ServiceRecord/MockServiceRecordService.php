<?php
declare(strict_types=1);

namespace Tests\Mocks\ServiceRecord;

use App\Services\Autocode\Enums\Filter;
use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockServiceRecordService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag): array
    {
        return [
            'data' => $this->recordStats(),
            'additional' => [
                'polling' => [
                    'synced_at' => now()->toIso8601ZuluString(),
                ],
                'parameters' => [
                    'gamertag' => $gamertag,
                    'filter' => Filter::MATCHMADE
                ]
            ]
        ];
    }

    public function recordStats(): array
    {
        return [
            'core' => [
                'summary' => [
                    'kills' => $this->faker->numberBetween(1, 25),
                    'deaths' => $this->faker->numberBetween(1, 25),
                    'assists' => $this->faker->numberBetween(1, 25),
                    'betrayals' => $this->faker->numberBetween(0, 5),
                    'suicides' => $this->faker->numberBetween(0, 5),
                    'spawns' => $this->faker->numberBetween(0, 5),
                    'max_killing_spree' => $this->faker->numberBetween(0, 5),
                    'vehicles' => [
                        'destroys' => $this->faker->numberBetween(0, 2),
                        'hijacks' => $this->faker->numberBetween(0, 2),
                    ],
                    'medals' => $this->faker->numberBetween(1, 25)
                ],
                'damage' => [
                    'taken' => $this->faker->numerify('######'),
                    'dealt' => $this->faker->numerify('######'),
                ],
                'shots' => [
                    'fired' => $this->faker->numerify(),
                    'landed' => $this->faker->numerify(),
                    'missed' => $this->faker->numerify(),
                    'accuracy' => $this->faker->randomFloat(2, 0, 100)
                ],
                'rounds' => [
                    'won' => $this->faker->numerify(),
                    'lost' => $this->faker->numerify(),
                    'tied' => $this->faker->numerify()
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
                        ]
                    ],
                    'assists' => [
                        'emp' => $this->faker->numberBetween(0, 5),
                        'driver' => $this->faker->numberBetween(0, 5),
                        'callouts' => $this->faker->numberBetween(0, 5)
                    ],
                    'vehicles' => [
                        'destroys' => [
                            [
                                'value' => 'warthog',
                                'count' => $this->faker->numberBetween(0, 5)
                            ]
                        ],
                        'hijacks' => [
                            [
                                'value' => 'warthog',
                                'count' => $this->faker->numberBetween(0, 5)
                            ]
                        ]
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
                'average_life_duration' => [
                    'seconds' => $this->faker->numberBetween(45, 60),
                    'human' => ''
                ],
                'scores' => [
                    'personal' => $this->faker->numerify('######'),
                    'points' => $this->faker->numerify('######'),
                ],
            ],
            'matches' => [
                'outcomes' => [
                    'wins' => $this->faker->numberBetween(0, 25),
                    'draws' => $this->faker->numberBetween(0, 25),
                    'losses' => $this->faker->numberBetween(0, 25),
                    'left' => $this->faker->numberBetween(0, 25),
                ],
                'total' => $this->faker->numberBetween(0, 25)
            ],
            'time_played' => [
                'seconds' => $this->faker->numerify('######'),
                'human' => ''
            ]
        ];
    }
}
