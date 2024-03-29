<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\AddGamer;

use App\Livewire\AddGamerForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\TestCase;

class InvalidGamerFormTest extends TestCase
{
    #[DataProvider('invalidTextDataProvider')]
    public function testInvalidTestSubmitted(?string $gamertag, string $validationError): void
    {
        // Arrange & Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertHasErrors([
                'gamertag' => $validationError,
            ]);
    }

    #[DataProvider('invalidApiDataProvider')]
    public function testInvalidResponseFromDotApi(callable $mockResponse, int $statusCode): void
    {
        // Arrange
        $mockResponse = call_user_func($mockResponse);

        Http::fake([
            '*' => Http::response($mockResponse, $statusCode),
        ]);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', 'foo')
            ->call('submit')
            ->assertHasErrors(['gamertag']);
    }

    public function testGracefulFallbackIfXuidNotFoundAndApiCallFails(): void
    {
        // Arrange
        $mockAppearanceResponse = (new MockAppearanceService())->success();
        $gamertag = Arr::get($mockAppearanceResponse, 'additional.params.gamertag');

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push(null, Response::HTTP_INTERNAL_SERVER_ERROR);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/'.$gamertag);
    }

    public static function invalidApiDataProvider(): array
    {
        return [
            401 => [
                'mockResponse' => fn () => (new MockAppearanceService())->error401(),
                'statusCode' => Response::HTTP_UNAUTHORIZED,
            ],
            403 => [
                'mockResponse' => fn () => (new MockAppearanceService())->error403(),
                'statusCode' => Response::HTTP_FORBIDDEN,
            ],
            404 => [
                'mockResponse' => fn () => (new MockAppearanceService())->error404(),
                'statusCode' => Response::HTTP_NOT_FOUND,
            ],
            429 => [
                'mockResponse' => fn () => (new MockAppearanceService())->error429(),
                'statusCode' => Response::HTTP_TOO_MANY_REQUESTS,
            ],
            500 => [
                'mockResponse' => fn () => (new MockAppearanceService())->error500(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ],
        ];
    }

    public static function invalidTextDataProvider(): array
    {
        return [
            'empty' => [
                'gamertag' => null,
                'validationError' => 'required',
            ],
            'empty string' => [
                'gamertag' => '',
                'validationError' => 'required',
            ],
            'too long' => [
                'gamertag' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnoprstuvwxyz',
                'validationError' => 'max',
            ],
        ];
    }
}
