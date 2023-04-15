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
                'total' => $this->faker->numberBetween(0, 5),
            ],
        ];
    }

    private function map(): array
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->word,
            'image_urls' => [
                'thumbnail' => $this->faker->imageUrl,
            ],
        ];
    }
}
