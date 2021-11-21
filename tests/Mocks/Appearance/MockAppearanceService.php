<?php
declare(strict_types=1);

namespace Tests\Mocks\Appearance;

use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\BaseMock;

class MockAppearanceService extends BaseMock
{
    public function success(): array
    {
        return [
            'data' => [
                'service_tag' => $this->faker->lexify('????'),
                'emblem_url' => $this->faker->url,
                'backdrop_image_url' => $this->faker->url,
            ],
            'additional' => [
                'gamertag' => $this->faker->word
            ]
        ];
    }

    public function error401(): array
    {
        return [
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'error' => 'Unauthorized',
            'message' => 'Missing Authentication'
        ];
    }

    public function error403(): array
    {
        return [
            'statusCode' => Response::HTTP_FORBIDDEN,
            'error' => 'Forbidden',
            'message' => 'Forbidden'
        ];
    }

    public function error404(): array
    {
        return [
            'statusCode' => Response::HTTP_NOT_FOUND,
            'error' => 'Not Found',
            'message' => 'Not Found'
        ];
    }

    public function error429(): array
    {
        return [
            'statusCode' => Response::HTTP_TOO_MANY_REQUESTS,
            'error' => 'Too Many Requests',
            'message' => 'Rate limit reached, please retry in 343 second(s)'
        ];
    }

    public function error500(): array
    {
        return [
            'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'error' => 'Internal Server Error',
            'message' => 'Something went wrong...'
        ];
    }
}
