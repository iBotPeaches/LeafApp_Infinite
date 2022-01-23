<?php
declare(strict_types=1);

namespace App\Services\FaceIt;

use App\Models\Championship;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ApiClient implements TournamentInterface
{
    private PendingRequest $pendingRequest;

    public function __construct(array $config)
    {
        $this->pendingRequest = Http::asJson()
            ->baseUrl($config['domain'] . '/data/v4/')
            ->withToken($config['key']);
    }

    public function championship(string $championshipId): Championship
    {
        $response = $this->pendingRequest->get('championships/' . $championshipId)->throw();
        $data = $response->json();

        return Championship::fromFaceItApi((array)$data);
    }
}
