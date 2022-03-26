<?php
declare(strict_types=1);

namespace Tests\Mocks\Appearance;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockAppearanceService extends BaseMock
{
    use HasErrorFunctions;

    public function success(?string $gamertag = null): array
    {
        return [
            'data' => [
                'emblem_url' => $this->faker->imageUrl,
                'backdrop_image_url' => $this->faker->imageUrl,
                'service_tag' => $this->faker->lexify('????'),
            ],
            'additional' => [
                'parameters' => [
                    'gamertag' => $gamertag ?? $this->faker->word
                ]
            ]
        ];
    }
}
