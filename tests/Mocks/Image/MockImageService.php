<?php

declare(strict_types=1);

namespace Tests\Mocks\Image;

use Tests\Mocks\BaseMock;

class MockImageService extends BaseMock
{
    public function success(): array
    {
        return [
            'output' => [
                'size' => $this->faker->randomNumber(1),
                'type' => 'image/png',
            ],
        ];
    }
}
