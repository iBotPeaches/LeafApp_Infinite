<?php
declare(strict_types=1);

namespace Tests\Mocks\Metadata;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockTeamsService extends BaseMock
{
    use HasErrorFunctions;

    public function success(): array
    {
        return [
            'data' => [
                $this->team(),
                $this->team(),
                $this->team(),
                $this->team(),
                $this->team(),
            ],
            'additional' => [
                'count' => $this->faker->numberBetween(0, 5),
                'parameters' => [
                    'ids' => []
                ]
            ]
        ];
    }

    private function team(): array
    {
        return [
            'id' => $this->faker->numberBetween(1, 25),
            'name' => $this->faker->word,
            'emblem_url' => $this->faker->imageUrl()
        ];
    }
}
