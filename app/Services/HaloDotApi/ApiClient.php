<?php
declare(strict_types=1);

namespace App\Services\HaloDotApi;

use App\Models\Player;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ApiClient implements InfiniteInterface
{
    private PendingRequest $pendingRequest;

    public function __construct(array $config)
    {
        $this->pendingRequest = Http::asJson()
            ->baseUrl($config['domain'] . '/games/hi/')
            ->withToken($config['key'], 'Cryptum-Token')
            ->withHeaders([
                'Cryptum-API-Version' => $config['version']
            ]);
    }

    public function appearance(string $gamertag): ?Player
    {
        $response = $this->pendingRequest->get('appearance/players/' . $gamertag);
        dd($response);
    }
}
