<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Jobs\PullAppearance;
use App\Jobs\PullXuid;
use App\Models\Category;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTeam;
use App\Models\Level;
use App\Models\Playlist;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Matches\MockMatchService;
use Tests\TestCase;

class GamePageTest extends TestCase
{
    public function testLoadingGamePageWithUnpulledGame(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()->createOne([
            'was_pulled' => false,
        ]);

        // Act
        $response = $this->get('/game/'.$game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertSeeLivewire('update-game-panel');
    }

    public function testLoadingGamePageWithRawValidUuid(): void
    {
        // Arrange
        Queue::fake([
            PullAppearance::class,
            PullXuid::class,
        ]);
        $mockMatchResponse = (new MockMatchService())->success('Test', 'Test2');

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        Team::factory()->createOne(['internal_id' => 0]);
        Team::factory()->createOne(['internal_id' => 1]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $uuid = Arr::get($mockMatchResponse, 'data.id');

        // Act
        $response = $this->get('/game/'.$uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');

        Queue::assertPushed(PullAppearance::class);
        Queue::assertPushed(PullXuid::class);
    }

    public function testLoadingGamePageWithOldGame(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()->createOne([
            'version' => '0.0.1',
            'was_pulled' => true,
        ]);

        // Act
        $response = $this->get('/game/'.$game->uuid);

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
            'input' => null,
        ]);

        $game = Game::factory()->createOne([
            'playlist_id' => $playlist->id,
        ]);

        // Act
        $response = $this->get('/game/'.$game->uuid);

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
            ->forMap(['name' => 'Bazaar'])
            ->forPlaylist(['name' => 'Unknown', 'is_ranked' => true])
            ->createOne([
                'version' => config('services.halodotapi.version'),
                'was_pulled' => true,
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
        $response = $this->get('/game/'.$game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertDontSeeLivewire('update-game-panel');
    }

    public function testLoadingGamePageWithUpToDateGameAlongsidePerformances(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()
            ->createOne([
                'version' => config('services.halodotapi.version'),
                'was_pulled' => true,
            ]);

        GamePlayer::factory()
            ->for($game)
            ->state(new Sequence(
                ['kills' => 1, 'expected_kills' => 2],
                ['kills' => 2, 'expected_kills' => 1],
                ['kills' => 2, 'expected_kills' => 2],
                ['kills' => 1, 'expected_kills' => null],
                ['deaths' => 1, 'expected_deaths' => 2],
                ['deaths' => 2, 'expected_deaths' => 1],
                ['deaths' => 2, 'expected_deaths' => 2],
                ['deaths' => 1, 'expected_deaths' => null],
            ))
            ->count(8)
            ->create();

        GameTeam::factory()
            ->for($game)
            ->createOne();

        // Act
        $response = $this->get('/game/'.$game->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-page');
        $response->assertDontSeeLivewire('update-game-panel');
    }
}
