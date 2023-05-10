<?php

declare(strict_types=1);

namespace Tests\Mocks\Xuid;

use Tests\Mocks\BaseMock;

class MockXuidService extends BaseMock
{
    public function success(string $gamertag, ?string $xuid = null): array
    {
        return [
            'data' => [
                'xuid' => $xuid ?? $this->faker->numerify('################'),
                'gamertag' => $gamertag,
                'gamertag_url' => $this->faker->imageUrl,
            ],
            'additional' => [
                'params' => [
                    'gamertag' => $gamertag,
                ],
            ],
        ];
    }
}
