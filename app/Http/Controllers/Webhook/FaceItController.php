<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomingFaceItRequest;
use App\Models\Championship;
use App\Services\FaceIt\TournamentInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class FaceItController extends Controller
{
    public function __invoke(IncomingFaceItRequest $request, TournamentInterface $client): JsonResponse
    {
        $payload = $request->input();

        if (Arr::get($payload, 'payload.entity.type') !== 'championship') {
            return response()->json(null);
        }

        $championshipId = Arr::get($payload, 'payload.entity.id');

        $championship = Championship::query()->firstWhere('faceit_id', $championshipId);
        if (! $championship) {
            $championship = $client->championship($championshipId);
        }

        $matchup = null;
        if ($championship) {
            $matchId = Arr::get($payload, 'payload.id');
            $matchup = $client->matchup($championship, $matchId);
        }

        return response()->json($matchup?->toArray());
    }
}
