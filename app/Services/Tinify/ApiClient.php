<?php

declare(strict_types=1);

namespace App\Services\Tinify;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ApiClient implements ImageInterface
{
    public function __construct(
        private readonly array $config
    ) {
        //
    }

    public function optimize(string $url): ?string
    {
        $response = $this->getPendingRequest()->post('shrink', [
            'source' => [
                'url' => $url,
            ],
        ])->throw();

        return $response->header('Location');
    }

    private function getPendingRequest(): PendingRequest
    {
        return Http::asJson()
            ->baseUrl($this->config['domain'])
            ->withBasicAuth('api', $this->config['key']);
    }
}
