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
        $response = $this->getPendingRequest()->get('/search/'.$gamertag);

        if ($response->successful() && Arr::get($response->json(), 'people.0.gamertag') === $gamertag) {
            return Arr::get($response->json(), 'people.0.xuid');
        }

        return null;
    }

    private function getPendingRequest(): PendingRequest
    {
        return Http::asJson()
            ->withHeaders([
                'X-Authorization' => config('services.xboxapi.key'),
            ])
            ->withUserAgent('Leaf - v'.config('sentry.release', 'dirty'))
            ->baseUrl($this->config['domain'].'/api/v2/');
    }
}
