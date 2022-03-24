<?php
declare(strict_types=1);

namespace Tests\Mocks\Metadata;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockMapsService extends BaseMock
{
    use HasErrorFunctions;

    public function success(): array
    {
        return [
            'data' => [
                $this->map(),
                $this->map(),
                $this->map(),
                $this->map(),
                $this->map(),
            ],
            'additional' => [
                'count' => $this->faker->numberBetween(0, 5),
                'parameters' => [
                    'ids' => []
                ]
            ]
        ];
    }

    private function map(): array
    {
        return [
            'level_id' => $this->faker->numberBetween(1, 500),
            'name' => $this->faker->word,
            'thumbnail_url' => $this->faker->imageUrl()
        ];
    }
}
