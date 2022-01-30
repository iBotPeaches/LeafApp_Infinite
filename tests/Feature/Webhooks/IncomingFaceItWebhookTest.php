<?php
declare(strict_types=1);

namespace Tests\Feature\Webhooks;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Championship\MockChampionshipService;
use Tests\Mocks\Championship\MockMatchupService;
use Tests\Mocks\Webhooks\MockMatchStatusFinished;
use Tests\TestCase;

class IncomingFaceItWebhookTest extends TestCase
{
    public function testIncomingFaceItMatchCompleted(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockMatchStatusFinished())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret')
        ];

        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockMatchupResponse = (new MockMatchupService())->success();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockMatchupResponse, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testIncomingFaceItMatchCompletedAsNotChampionship(): void
    {
        // Arrange & Act
        $payload = (new MockMatchStatusFinished())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret')
        ];

        Arr::set($payload, 'payload.entity.type', 'not-championship');
        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testIncomingEmptyFaceItData(): void
    {
        // Arrange & Act
        $payload = (new MockMatchStatusFinished())->error();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret')
        ];

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
