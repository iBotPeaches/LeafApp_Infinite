<?php
declare(strict_types=1);

namespace Tests\Mocks\Metadata;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockPlaylistsService extends BaseMock
{
    use HasErrorFunctions;

    public function success(): array
    {
        return [
            'data' => [
                $this->playlist(),
                $this->playlist(),
                $this->playlist(),
                $this->playlist(),
                $this->playlist(),
            ],
            'additional' => [
                'count' => $this->faker->numberBetween(0, 5),
                'parameters' => [
                    'ids' => []
                ]
            ]
        ];
    }

    private function playlist(): array
    {
        return [
            'name' => $this->faker->word,
            'asset' => [
                'id' => $this->faker->uuid,
                'version' => $this->faker->uuid,
                'thumbnail_url' => $this->faker->imageUrl
            ],
            'availability' => [
                'start_date' => now()->toIso8601ZuluString(),
                'end_date' => now()->toIso8601ZuluString()
            ],
            'properties' => [
                'queue' => 'solo-duo',
                'input' => 'mnk',
                'ranked' => $this->faker->boolean
            ]
        ];
    }
}
