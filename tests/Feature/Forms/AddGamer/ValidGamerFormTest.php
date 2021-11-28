<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\AddGamer;

use App\Http\Livewire\AddGamerForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
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
            'service_tag' => Arr::get($mockResponse, 'data.service_tag')
        ]);
    }
}
