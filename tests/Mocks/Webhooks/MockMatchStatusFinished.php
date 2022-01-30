<?php
declare(strict_types=1);

namespace Tests\Mocks\Webhooks;

use Tests\Mocks\BaseMock;

class MockMatchStatusFinished extends BaseMock
{
    public function error(): array
    {
        return [];
    }

    public function success(): array
    {
        return [
            'transaction_id' => $this->faker->uuid,
            'event' => 'match_status_finished',
            'event_id' => $this->faker->uuid,
            'third_party_id' => $this->faker->uuid,
            'app_id' => $this->faker->uuid,
            'timestamp' => now()->toIso8601ZuluString(),
            'retry_count' => 0,
            'version' => 1,
            'payload' => [
                'id' => '1-' . $this->faker->uuid,
                'organizer_id' => $this->faker->uuid,
                'region' => 'NA',
                'game' => 'halo_infinite',
                'version' => 1,
                'entity' => [
                    'id' => $this->faker->uuid,
                    'name' => 'HCS Something',
                    'type' => 'championship'
                ]
            ]
        ];
    }
}
