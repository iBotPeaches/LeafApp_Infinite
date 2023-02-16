<?php

declare(strict_types=1);

namespace App\Services\XboxApi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ApiClient implements XboxInterface
{
    public function __construct(
        private readonly array $config
    ) {
        //
    }

    public function xuid(string $gamertag): ?string
    {
        $response = $this->getPendingRequest()->get('/xuid/'.$gamertag);

        if ($response->successful()) {
            return Arr::get($response->json(), 'xuid');
        }

        return null;
    }

    private function getPendingRequest(): PendingRequest
    {
        return Http::asJson()
            ->withUserAgent('Leaf - v'.config('services.autocode.version', 'dirty'))
            ->baseUrl($this->config['domain']);
    }
}
