<?php

declare(strict_types=1);

namespace Tests\Feature\Webhooks;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Championship\MockChampionshipBracketService;
use Tests\Mocks\Championship\MockChampionshipService;
use Tests\Mocks\Championship\MockMatchupService;
use Tests\Mocks\Webhooks\MockChampionshipCancelled;
use Tests\Mocks\Webhooks\MockChampionshipCreated;
use Tests\Mocks\Webhooks\MockChampionshipFinished;
use Tests\Mocks\Webhooks\MockChampionshipStarted;
use Tests\Mocks\Webhooks\MockMatchObjectCreated;
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
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
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

    public function testIncomingFaceItMatchObjectCreated(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockMatchObjectCreated())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
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

    public function testIncomingFaceItChampionshipStarted(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipStarted())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
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

    public function testIncomingFaceItChampionshipCompleted(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipFinished())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testIncomingFaceItChampionshipCancelled(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipCancelled())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testIncomingFaceItChampionshipCreated(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipCreated())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testIncomingFaceItMatchCompletedAsNotChampionship(): void
    {
        // Arrange & Act
        $payload = (new MockMatchStatusFinished())->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        Arr::set($payload, 'payload.entity.type', 'not-championship');
        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @dataProvider emptyFaceItDataProvider */
    public function testIncomingEmptyFaceItData(array $payload): void
    {
        // Arrange & Act
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public static function emptyFaceItDataProvider(): array
    {
        return [
            [
                'payload' => (new MockMatchStatusFinished())->error(),
            ],
            [
                'payload' => (new MockMatchObjectCreated())->error(),
            ],
            [
                'payload' => (new MockChampionshipStarted())->error(),
            ],
            [
                'payload' => (new MockChampionshipCancelled())->error(),
            ],
            [
                'payload' => (new MockChampionshipCreated())->error(),
            ],
        ];
    }
}
