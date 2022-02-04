<?php
declare(strict_types=1);

namespace App\Services\XboxApi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ApiClient implements XboxInterface
{
    private PendingRequest $pendingRequest;

    public function __construct(array $config)
    {
        $this->pendingRequest = Http::asJson()
            ->withUserAgent('Leaf - v' . config('services.autocode.version', 'dirty'))
            ->baseUrl($config['domain']);
    }

    public function xuid(string $gamertag): ?string
    {
        $response = $this->pendingRequest->get('/xuid/' . $gamertag);

        if ($response->successful()) {
            return Arr::get($response->json(), 'xuid');
        }

        return null;
    }
}
