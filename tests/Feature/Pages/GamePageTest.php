<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GamePageTest extends TestCase
{
    public function testLoadingGamePageWithUnpulledGame(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()->createOne([
            'was_pulled' => false
        ]);

        // Act
        $response = $this->get('/game/' . $game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertSeeLivewire('update-game-panel');
    }

    public function testLoadingGamePageWithOldGame(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()->createOne([
            'version' => '0.0.1',
            'was_pulled' => true
        ]);

        // Act
        $response = $this->get('/game/' . $game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertSeeLivewire('update-game-panel');
    }

    public function testLoadingGamePageWithUpToDateGame(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()
            ->forPlaylist(['name' => 'Unknown'])
            ->createOne([
                'version' => config('services.autocode.version'),
                'was_pulled' => true
            ]);

        GamePlayer::factory()
            ->for($game)
            ->state(new Sequence(
                ['pre_csr' => null],
                ['pre_csr' => 1500],
            ))
            ->count(2)
            ->create();

        // Act
        $response = $this->get('/game/' . $game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertDontSeeLivewire('update-game-panel');
    }
}
