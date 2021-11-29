<?php
declare(strict_types=1);

namespace Tests\Mocks\Xuid;

use Tests\Mocks\BaseMock;

class MockXuidService extends BaseMock
{
    public function success(string $gamertag): array
    {
        return [
            'gamertag' => $gamertag,
            'xuid' => $this->faker->numerify('################'),
        ];
    }
}
