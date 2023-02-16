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
                'count' => $this->faker->numberBetween(0, 5),
                'parameters' => [
                    'ids' => [],
                ],
            ],
        ];
    }

    private function category(): array
    {
        return [
            'category_id' => $this->faker->randomNumber(1),
            'name' => $this->faker->word,
            'thumbnail_url' => $this->faker->imageUrl,
        ];
    }
}
