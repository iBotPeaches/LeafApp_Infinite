<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\AddGamer;

use App\Http\Livewire\AddGamerForm;
use App\Models\Player;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\Xuid\MockXuidService;
use Tests\TestCase;

class ValidGamerFormTest extends TestCase
{
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        $mockResponse = (new MockAppearanceService())->success();
        $gamertag = Arr::get($mockResponse, 'additional.gamertag');

        Http::fake([
            '*' => Http::response($mockResponse, Response::HTTP_OK)
        ]);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/' . $gamertag);

        $this->assertDatabaseHas('players', [
            'gamertag' => $gamertag,
            'service_tag' => Arr::get($mockResponse, 'data.service_tag'),
            'emblem_url' => Arr::get($mockResponse, 'data.emblem_url'),
            'backdrop_url' => Arr::get($mockResponse, 'data.backdrop_image_url'),
        ]);
    }

    public function testValidResponseFromXuidServiceIfNoXuidFound(): void
    {
        // Arrange
        $mockAppearanceResponse = (new MockAppearanceService())->success();
        $gamertag = Arr::get($mockAppearanceResponse, 'additional.gamertag');
        $mockXuidResponse = (new MockXuidService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockXuidResponse, Response::HTTP_OK);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/' . $gamertag);
    }

    public function testValidResponseIfXuidServiceDisabled(): void
    {
        // Arrange
        Config::set('services.xboxapi.enabled', false);
        $mockAppearanceResponse = (new MockAppearanceService())->success();
        $gamertag = Arr::get($mockAppearanceResponse, 'additional.gamertag');

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/' . $gamertag);
    }

    public function testGracefulFallbackIfXuidNotFound(): void
    {
        // Arrange
        $mockAppearanceResponse = (new MockAppearanceService())->success();
        $gamertag = Arr::get($mockAppearanceResponse, 'additional.gamertag');
        $mockXuidResponse = (new MockXuidService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockXuidResponse, Response::HTTP_OK);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/' . $gamertag);
    }

    public function testValidResponseFromHaloDotApiIfAccountAlreadyExists(): void
    {
        // Arrange
        $mockResponse = (new MockAppearanceService())->success();
        $gamertag = Arr::get($mockResponse, 'additional.gamertag');
        Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        Http::fake([
            '*' => Http::response($mockResponse, Response::HTTP_OK)
        ]);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/' . $gamertag);
    }
}
