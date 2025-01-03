<?php

declare(strict_types=1);

namespace Tests\Feature\Webhooks;

use App\Enums\FaceItStatus;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\DataProvider;
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
    public function test_incoming_face_it_match_completed(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockMatchStatusFinished)->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService)->success();
        $mockMatchupResponse = (new MockMatchupService)->success();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockMatchupResponse, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_incoming_face_it_match_object_created(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockMatchObjectCreated)->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService)->success();
        $mockMatchupResponse = (new MockMatchupService)->success();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockMatchupResponse, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_incoming_face_it_championship_started(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipStarted)->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService)->success();
        $mockMatchupResponse = (new MockMatchupService)->success();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockMatchupResponse, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_incoming_face_it_championship_completed(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipFinished)->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService)->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService)->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService)->empty();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_incoming_face_it_championship_cancelled(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipCancelled)->success();
        $championshipId = Arr::get($payload, 'payload.id');
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService)->success($championshipId);
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService)->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService)->empty();

        Arr::set($mockChampionshipResponse, 'status', 'cancelled');

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('championships', [
            'faceit_id' => Arr::get($payload, 'payload.id'),
            'status' => FaceItStatus::CANCELLED,
        ]);
    }

    public function test_incoming_face_it_championship_created(): void
    {
        // Arrange & Act
        Queue::fake();
        $payload = (new MockChampionshipCreated)->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        $mockChampionshipResponse = (new MockChampionshipService)->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService)->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService)->empty();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_incoming_face_it_match_completed_as_not_championship(): void
    {
        // Arrange & Act
        $payload = (new MockMatchStatusFinished)->success();
        $headers = [
            'X-Cat-Dog' => config('services.faceit.webhook.secret'),
        ];

        Arr::set($payload, 'payload.entity.type', 'not-championship');
        $response = $this->postJson(route('webhooks.faceit'), $payload, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    #[DataProvider('emptyFaceItDataProvider')]
    public function test_incoming_empty_face_it_data(callable $payloadFunction): void
    {
        // Arrange & Act
        $payload = $payloadFunction();
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
                'payloadFunction' => fn () => (new MockMatchStatusFinished)->error(),
            ],
            [
                'payloadFunction' => fn () => (new MockMatchObjectCreated)->error(),
            ],
            [
                'payloadFunction' => fn () => (new MockChampionshipStarted)->error(),
            ],
            [
                'payloadFunction' => fn () => (new MockChampionshipCancelled)->error(),
            ],
            [
                'payloadFunction' => fn () => (new MockChampionshipCreated)->error(),
            ],
        ];
    }
}
