<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTeam;
use App\Models\Playlist;
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

    public function testLoadingGamePageWithSocialGame(): void
    {
        // Arrange
        Http::fake();

        $playlist = Playlist::factory()->createOne([
            'is_ranked' => 1,
            'queue' => null,
            'input' => null
        ]);

        $game = Game::factory()->createOne([
            'playlist_id' => $playlist->id
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
            ->forPlaylist(['name' => 'Unknown', 'is_ranked' => true])
            ->createOne([
                'version' => config('services.autocode.version'),
                'was_pulled' => true
            ]);

        GamePlayer::factory()
            ->for($game)
            ->withMedals()
            ->state(new Sequence(
                ['pre_csr' => null],
                ['pre_csr' => 1500],
                ['pre_csr' => 1500, 'post_csr' => 1500],
                ['pre_csr' => 1491, 'post_csr' => 1501],
            ))
            ->count(4)
            ->create();

        GameTeam::factory()
            ->for($game)
            ->createOne();

        // Act
        $response = $this->get('/game/' . $game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertDontSeeLivewire('update-game-panel');
    }
}
