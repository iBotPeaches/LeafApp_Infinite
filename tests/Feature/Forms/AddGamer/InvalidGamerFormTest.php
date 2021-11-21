<?php

namespace Tests\Feature\Forms\AddGamer;

use App\Http\Livewire\AddGamerForm;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\TestCase;

class InvalidGamerFormTest extends TestCase
{
    /** @dataProvider invalidTextDataProvider */
    public function testInvalidTestSubmitted(?string $gamertag, string $validationError): void
    {
        // Arrange & Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertHasErrors([
                'gamertag' => $validationError
            ]);
    }

    /** @dataProvider invalidApiDataProvider */
    public function testInvalidResponseFromHaloDotApi(callable $mockResponse, int $statusCode): void
    {
        // Arrange
        $mockResponse = call_user_func($mockResponse);

        Http::fake([
            '*' => Http::response($mockResponse, $statusCode)
        ]);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', 'foo')
            ->call('submit')
            ->assertHasErrors(['gamertag']);
    }

    public function invalidApiDataProvider(): array
    {
        return [
            401 => [
                'response' => fn() => (new MockAppearanceService())->error401(),
                'statusCode' => Response::HTTP_UNAUTHORIZED
            ],
            403 => [
                'response' => fn() => (new MockAppearanceService())->error403(),
                'statusCode' => Response::HTTP_FORBIDDEN
            ],
            404 => [
                'response' => fn() => (new MockAppearanceService())->error404(),
                'statusCode' => Response::HTTP_NOT_FOUND
            ],
            429 => [
                'response' => fn() => (new MockAppearanceService())->error429(),
                'statusCode' => Response::HTTP_TOO_MANY_REQUESTS
            ],
            500 => [
                'response' => fn() => (new MockAppearanceService())->error500(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
            ]
        ];
    }

    public function invalidTextDataProvider(): array
    {
        return [
            'empty' => [
                'gamertag' => null,
                'validation' => 'required'
            ],
            'too long' => [
                'gamertag' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnoprstuvwxyz',
                'validation' => 'max'
            ]
        ];
    }
}
