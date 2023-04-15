<?php

declare(strict_types=1);

namespace Tests\Mocks\Metadata;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockCategoriesService extends BaseMock
{
    use HasErrorFunctions;

    public function success(): array
    {
        return [
            'data' => [
                $this->category(),
                $this->category(),
                $this->category(),
                $this->category(),
                $this->category(),
            ],
            'additional' => [
                'total' => $this->faker->numberBetween(0, 5),
            ],
        ];
    }

    private function category(): array
    {
        return [
            'id' => $this->faker->randomNumber(1),
            'name' => $this->faker->word,
            'image_urls' => [
                'thumbnail' => $this->faker->imageUrl,
            ],
        ];
    }
}
