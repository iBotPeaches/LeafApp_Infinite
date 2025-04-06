<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\Outcome;
use App\Jobs\ProcessPlaylistAnalytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\PlaylistAnalytic;
use App\Support\Analytics\AnalyticInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\HasAnalyticDataProvider;

class ProcessPlaylistAnalyticTest extends TestCase
{
    use HasAnalyticDataProvider;
    use WithFaker;

    #[DataProvider('playlistDataProvider')]
    public function test_processing_each_category(AnalyticInterface $analyticClass): void
    {
        // Arrange
        /** @var Game $game */
        $game = Game::factory()
            ->forPlaylist(['is_ranked' => false])
            ->create();

        Http::fake()->preventStrayRequests();
        GamePlayer::factory()
            ->for(
                Game::factory()
                    ->forPlaylist(['is_ranked' => true])
                    ->forMap([])
            )
            ->sequence(
                ['deaths' => 0, 'outcome' => Outcome::LEFT],
                ['deaths' => 0, 'outcome' => Outcome::WIN],
            )
            ->count(2)
            ->create();

        GamePlayer::factory()
            ->withMedals()
            ->for($game)
            ->sequence(
                ['deaths' => 0, 'outcome' => Outcome::LEFT],
                ['deaths' => 0, 'outcome' => Outcome::WIN],
            )
            ->count(2)
            ->create();

        assert($game->playlist !== null);

        // Act
        ProcessPlaylistAnalytic::dispatchSync($game->playlist);

        // Assert
        $this->assertDatabaseHas(PlaylistAnalytic::class, [
            'playlist_id' => $game->playlist_id,
            'key' => $analyticClass->key(),
        ]);
    }
}
