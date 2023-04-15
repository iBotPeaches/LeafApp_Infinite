<?php

declare(strict_types=1);

namespace Tests\Mocks\Metadata;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockMedalsService extends BaseMock
{
    use HasErrorFunctions;

    public function success(): array
    {
        return [
            'data' => [
                $this->medal(),
                $this->medal(),
                $this->medal(),
                $this->medal(),
                $this->medal(),
            ],
        ];
    }

    private function medal(): array
    {
        return [
            'id' => $this->faker->numberBetween(1, 500),
            'name' => $this->faker->word,
            'description' => $this->faker->words(5, true),
            'image_urls' => [
                'small' => $this->faker->imageUrl,
                'medium' => $this->faker->imageUrl,
                'large' => $this->faker->imageUrl,
            ],
            'attributes' => [
                'difficulty' => $this->faker->randomElement(['normal', 'mythic', 'legendary']),
            ],
            'properties' => [
                'type' => $this->faker->randomElement(['mode', 'proficiency']),
            ],
        ];
    }
}
