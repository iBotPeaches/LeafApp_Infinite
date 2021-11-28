<?php
declare(strict_types=1);

namespace Tests\Mocks\ServiceRecord;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockServiceRecordService extends BaseMock
{
    use HasErrorFunctions;

    public function success(string $gamertag): array
    {
        return [
            'data' => [
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
                    'taken' => $this->faker->numerify('######'),
                    'dealt' => $this->faker->numerify('######'),
                    'average' => $this->faker->numerify('####')
                ],
                'shots' => [
                    'fired' => $this->faker->numerify(),
                    'landed' => $this->faker->numerify(),
                    'missed' => $this->faker->numerify(),
                    'accuracy' => $this->faker->randomFloat(2, 0, 100)
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
                    'matches' => [
                        'wins' => $this->faker->numberBetween(0, 25),
                        'losses' => $this->faker->numberBetween(0, 25),
                        'left' => $this->faker->numberBetween(0, 25),
                        'draws' => $this->faker->numberBetween(0, 25),
                    ]
                ],
                'kda' => $this->faker->randomFloat(2, 0, 10),
                'kdr' => $this->faker->randomFloat(2, 0, 10),
                'total_score' => $this->faker->numerify('######'),
                'matches_played' => $this->faker->numerify(),
                'time_played' => [
                    'seconds' => $this->faker->numerify('######'),
                    'human' => ''
                ],
                'win_rate' => $this->faker->numerify('##.##'),
            ],
            'additional' => [
                'gamertag' => $gamertag,
            ]
        ];
    }
}
