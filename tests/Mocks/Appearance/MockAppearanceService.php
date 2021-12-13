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
                'service_tag' => $this->faker->lexify('????'),
                'emblem_url' => $this->faker->url,
                'backdrop_image_url' => $this->faker->url,
            ],
            'additional' => [
                'gamertag' => $gamertag ?? $this->faker->word
            ]
        ];
    }
}
