<?php

declare(strict_types=1);

namespace Tests\Mocks\Webhooks;

use App\Services\FaceIt\Enums\WebhookEvent;
use Tests\Mocks\BaseMock;

class MockChampionshipStarted extends BaseMock
{
    public function error(): array
    {
        return [];
    }

    public function success(): array
    {
        return [
            'transaction_id' => $this->faker->uuid,
            'event' => WebhookEvent::CHAMPIONSHIP_STARTED,
            'event_id' => $this->faker->uuid,
            'third_party_id' => $this->faker->uuid,
            'app_id' => $this->faker->uuid,
            'timestamp' => now()->toIso8601ZuluString(),
            'retry_count' => 0,
            'version' => 1,
            'payload' => [
                'id' => $this->faker->uuid,
                'organizer_id' => $this->faker->uuid,
                'game' => 'halo_infinite',
            ],
        ];
    }
}
