<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\AddGamer;

use App\Livewire\AddGamerForm;
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
    public function test_valid_response_from_dot_api(): void
    {
        // Arrange
        $mockResponse = (new MockAppearanceService)->success();
        $gamertag = Arr::get($mockResponse, 'additional.params.gamertag');

        Http::fake([
            '*' => Http::response($mockResponse, Response::HTTP_OK),
        ]);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/'.$gamertag);

        $this->assertDatabaseHas('players', [
            'gamertag' => $gamertag,
            'service_tag' => Arr::get($mockResponse, 'data.service_tag'),
            'emblem_url' => Arr::get($mockResponse, 'data.image_urls.emblem'),
            'backdrop_url' => Arr::get($mockResponse, 'data.image_urls.backdrop'),
        ]);
    }

    public function test_valid_response_from_xuid_service_if_no_xuid_found(): void
    {
        // Arrange
        $mockAppearanceResponse = (new MockAppearanceService)->success();
        $gamertag = Arr::get($mockAppearanceResponse, 'additional.params.gamertag');
        $mockXuidResponse = (new MockXuidService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockXuidResponse, Response::HTTP_OK);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/'.$gamertag);

        $this->assertDatabaseHas('players', [
            'gamertag' => $gamertag,
            'xuid' => Arr::get($mockXuidResponse, 'data.xuid'),
        ]);
    }

    public function test_valid_response_if_xuid_service_disabled(): void
    {
        // Arrange
        Config::set('services.dotapi.xuid_disabled', true);
        $mockAppearanceResponse = (new MockAppearanceService)->success();
        $gamertag = Arr::get($mockAppearanceResponse, 'additional.params.gamertag');

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/'.$gamertag);
    }

    public function test_graceful_fallback_if_xuid_not_found(): void
    {
        // Arrange
        $mockAppearanceResponse = (new MockAppearanceService)->success();
        $gamertag = Arr::get($mockAppearanceResponse, 'additional.params.gamertag');
        $mockXuidResponse = (new MockXuidService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockXuidResponse, Response::HTTP_OK);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/'.$gamertag);
    }

    public function test_valid_response_from_dot_api_if_account_already_exists(): void
    {
        // Arrange
        $mockResponse = (new MockAppearanceService)->success();
        $gamertag = Arr::get($mockResponse, 'additional.params.gamertag');
        Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        Http::fake([
            '*' => Http::response($mockResponse, Response::HTTP_OK),
        ]);

        // Act & Assert
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertRedirect('/player/'.$gamertag);
    }
}
