<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GamePageTest extends TestCase
{
    public function testLoadingGamePage(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()->createOne();

        // Act
        $response = $this->get('/game/' . $game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertSeeLivewire('update-game-panel');
    }
}
