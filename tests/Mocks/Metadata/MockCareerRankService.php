<?php

declare(strict_types=1);

namespace Tests\Mocks\Metadata;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockCareerRankService extends BaseMock
{
    use HasErrorFunctions;

    public function success(): array
    {
        return [
            'data' => [
                [
                    'rank' => 1,
                    'title' => 'Recruit',
                    'subtitle' => '',
                    'image_urls' => [
                        'icon' => $this->faker->imageUrl,
                        'large_icon' => $this->faker->imageUrl,
                        'adornment_icon' => $this->faker->imageUrl,
                    ],
                    'attributes' => [
                        'tier' => null,
                        'grade' => 1,
                        'colors' => [

                        ],
                    ],
                    'properties' => [
                        'type' => 'Bronze',
                        'threshold' => 100,
                    ],
                ],
            ],
            'additional' => [
                'total' => 1,
            ],
        ];
    }
}
