<?php

declare(strict_types=1);

namespace Tests\Mocks\CareerRank;

use Tests\Mocks\BaseMock;

class MockCareerRankService extends BaseMock
{
    public function success(string $gamertag = ''): array
    {
        return [
            'data' => [
                'current' => $this->careerBlock(),
                'next' => $this->careerBlock(),
            ],
            'additional' => [
                'params' => [
                    'gamertag' => $gamertag,
                ],
                'query' => [
                    'language' => 'en-US',
                ],
            ],
        ];
    }

    private function careerBlock(): array
    {
        return [
            'rank' => 12,
            'title' => 'Corporal',
            'subtitle' => 'Bronze',
            'progression' => 14510,
            'image_urls' => [
                'icon' => $this->faker->imageUrl,
                'large_icon' => $this->faker->imageUrl,
                'adornment_icon' => $this->faker->imageUrl,
            ],
            'attributes' => [
                'tier' => 2,
                'grade' => 2,
            ],
            'properties' => [
                'type' => 'Bronze',
                'threshold' => 15100,
            ],
        ];
    }
}
