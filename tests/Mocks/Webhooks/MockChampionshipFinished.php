<?php
declare(strict_types=1);

namespace Tests\Mocks\Webhooks;

use App\Services\FaceIt\Enums\WebhookEvent;
use Tests\Mocks\BaseMock;

class MockChampionshipFinished extends BaseMock
{
    public function error(): array
    {
        return [];
    }

    public function success(): array
    {
        return [
            'transaction_id' => $this->faker->uuid,
            'event' => WebhookEvent::CHAMPIONSHIP_FINISHED,
            'event_id' => $this->faker->uuid,
            'third_party_id' => $this->faker->uuid,
            'app_id' => $this->faker->uuid,
            'timestamp' => now()->toIso8601ZuluString(),
            'retry_count' => 0,
            'version' => 1,
            'payload' => [
                'id' => '1-' . $this->faker->uuid,
                'organizer_id' => $this->faker->uuid,
                'game' => 'halo_infinite',
            ]
        ];
    }
}
