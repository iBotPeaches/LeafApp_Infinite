<?php

declare(strict_types=1);

namespace Tests\Mocks\Metadata;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockSeasonService extends BaseMock
{
    use HasErrorFunctions;

    public function success(): array
    {
        return [
            'data' => [
                $this->season(),
                $this->season(),
            ],
            'additional' => [
                'total' => $this->faker->numberBetween(0, 5),
                'query' => [
                    'language' => 'en-US',
                ],
            ],
        ];
    }

    private function season(): array
    {
        return [
            'id' => $this->faker->numberBetween(1, 2),
            'version' => 1,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'narrative_blurb' => $this->faker->sentence,
            'image_urls' => [
                'season_logo' => $this->faker->imageUrl,
                'card_background' => $this->faker->imageUrl,
                'battlepass_background' => $this->faker->imageUrl,
            ],
            'properties' => [
                'identifier' => 'Season1-2',
                'csr' => 'CsrSeason1-2',
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
