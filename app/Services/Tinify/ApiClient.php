<?php
declare(strict_types=1);

namespace App\Services\Tinify;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ApiClient implements ImageInterface
{
    private PendingRequest $pendingRequest;

    public function __construct(array $config)
    {
        $this->pendingRequest = Http::asJson()
            ->baseUrl($config['domain'])
            ->withBasicAuth('api', $config['key']);
    }

    public function optimize(string $url): ?string
    {
        $response = $this->pendingRequest->post('shrink', [
            'source' => [
                'url' => $url
            ]
        ])->throw();

        return $response->header('Location');
    }
}
