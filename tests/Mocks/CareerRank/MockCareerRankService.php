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
                'level' => [
                    'total_xp' => 900,
                    'remaining_xp_to_next_level' => 100,
                    'next_level_threshold' => 1000,
                ],
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
            'image_urls' => [
                'icon' => $this->faker->imageUrl,
                'large_icon' => $this->faker->imageUrl,
                'adornment_icon' => $this->faker->imageUrl,
            ],
            'attributes' => [
                'tier' => 2,
                'grade' => 2,
                'colors' => [

                ],
            ],
            'properties' => [
                'type' => 'Bronze',
                'threshold' => 15100,
            ],
        ];
    }
}
