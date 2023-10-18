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
                $this->playlist('Super Husky Raid:CTF on Smallhalla'),
                $this->playlist(),
                $this->playlist(),
                $this->playlist(),
            ],
            'additional' => [
                'total' => $this->faker->numberBetween(0, 5),
                'query' => [
                    'language' => 'en-US',
                ],
            ],
        ];
    }

    private function playlist(string $name = null): array
    {
        return [
            'id' => $this->faker->uuid,
            'version' => $this->faker->uuid,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'image_urls' => [
                'hero' => $this->faker->imageUrl,
                'thumbnail' => $this->faker->imageUrl,
                'screenshots' => [
                    $this->faker->imageUrl,
                ],
            ],
            'attributes' => [
                'active' => true,
                'featured' => true,
                'ranked' => true,
            ],
            'properties' => [
                'queue' => 'solo-duo',
                'input' => 'mnk',
                'experience' => 'arena',
            ],
            'rotation' => [
                [
                    'name' => $name ?? $this->faker->word,
                    'weight' => $this->faker->numberBetween(100, 115),
                ],
            ],
            'availability' => [
                [
                    'start_date' => now()->toIso8601ZuluString(),
                    'end_date' => now()->toIso8601ZuluString(),
                ],
            ],
        ];
    }
}
