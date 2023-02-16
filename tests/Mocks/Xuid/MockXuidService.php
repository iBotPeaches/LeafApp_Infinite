<?php

declare(strict_types=1);

namespace Tests\Mocks\Xuid;

use Tests\Mocks\BaseMock;

class MockXuidService extends BaseMock
{
    public function success(string $gamertag, ?string $xuid = null): array
    {
        return [
            'gamertag' => $gamertag,
            'xuid' => $xuid ?? $this->faker->numerify('################'),
        ];
    }
}
