<?php

declare(strict_types=1);

namespace Tests\Mocks\Webhooks;

use App\Services\FaceIt\Enums\WebhookEvent;
use Tests\Mocks\BaseMock;

class MockMatchObjectCreated extends BaseMock
{
    public function error(): array
    {
        return [
            'event' => WebhookEvent::MATCH_OBJECT_CREATED,
        ];
    }

    public function success(): array
    {
        return [
            'transaction_id' => $this->faker->uuid,
            'event' => WebhookEvent::MATCH_OBJECT_CREATED,
            'event_id' => $this->faker->uuid,
            'third_party_id' => $this->faker->uuid,
            'app_id' => $this->faker->uuid,
            'timestamp' => now()->toIso8601ZuluString(),
            'retry_count' => 0,
            'version' => 1,
            'payload' => [
                'id' => $this->faker->uuid,
                'organizer_id' => $this->faker->uuid,
                'region' => 'NA',
                'game' => 'halo_infinite',
                'version' => 1,
                'entity' => [
                    'id' => $this->faker->uuid,
                    'name' => 'HCS Something',
                    'type' => 'championship',
                ],
                'created_at' => now()->toIso8601ZuluString(),
                'updated_at' => now()->toIso8601ZuluString(),
            ],
        ];
    }
}
