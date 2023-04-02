<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomingFaceItRequest;
use App\Models\Championship;
use App\Models\Matchup;
use App\Services\FaceIt\Enums\WebhookEvent;
use App\Services\FaceIt\TournamentInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class FaceItController extends Controller
{
    public function __invoke(IncomingFaceItRequest $request, TournamentInterface $client): JsonResponse
    {
        $payload = $request->input();
        $type = Arr::get($payload, 'event');

        return match ($type) {
            WebhookEvent::MATCH_STATUS_FINISHED => $this->parseMatchStatusFinished($client, $payload),
            WebhookEvent::CHAMPIONSHIP_CREATED => $this->parseChampionshipCreated($client, $payload),
            WebhookEvent::CHAMPIONSHIP_CANCELLED => $this->parseChampionshipCancelled($client, $payload),
            WebhookEvent::CHAMPIONSHIP_FINISHED => $this->parseChampionshipFinished($client, $payload),
            WebhookEvent::CHAMPIONSHIP_STARTED => $this->parseChampionshipStarted($client, $payload),
            WebhookEvent::MATCH_OBJECT_CREATED => $this->parseMatchObjectCreated($client, $payload),
            default => response()->json()
        };
    }

    private function parseChampionshipCreated(TournamentInterface $client, array $payload): JsonResponse
    {
        $championship = $this->parseGenericChampionshipPayload($client, $payload);

        return response()->json($championship?->toArray());
    }

    private function parseChampionshipCancelled(TournamentInterface $client, array $payload): JsonResponse
    {
        $championship = $this->parseGenericChampionshipPayload($client, $payload);

        return response()->json($championship?->toArray());
    }

    private function parseChampionshipFinished(TournamentInterface $client, array $payload): JsonResponse
    {
        $championship = $this->parseGenericChampionshipPayload($client, $payload);

        return response()->json($championship?->toArray());
    }

    private function parseChampionshipStarted(TournamentInterface $client, array $payload): JsonResponse
    {
        $championship = $this->parseGenericChampionshipPayload($client, $payload);

        return response()->json($championship?->toArray());
    }

    private function parseMatchObjectCreated(TournamentInterface $client, array $payload): JsonResponse
    {
        $matchup = $this->parseGenericMatchPayload($client, $payload);

        return response()->json($matchup?->toArray());
    }

    private function parseMatchStatusFinished(TournamentInterface $client, array $payload): JsonResponse
    {
        $matchup = $this->parseGenericMatchPayload($client, $payload);

        return response()->json($matchup?->toArray());
    }

    private function parseGenericChampionshipPayload(TournamentInterface $client, array $payload): ?Championship
    {
        $championshipId = Arr::get($payload, 'payload.id');
        $championship = $client->championship($championshipId);

        if ($championship) {
            $client->bracket($championship);
        }

        return $championship;
    }

    private function parseGenericMatchPayload(TournamentInterface $client, array $payload): ?Matchup
    {
        if (Arr::get($payload, 'payload.entity.type') !== 'championship') {
            return null;
        }

        $championshipId = Arr::get($payload, 'payload.entity.id');
        $championship = $client->championship($championshipId);

        $matchup = null;
        if ($championship) {
            $matchId = Arr::get($payload, 'payload.id');
            $matchup = $client->matchup($championship, $matchId);
        }

        return $matchup;
    }
}
