<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\AnalyticKey;
use App\Enums\Outcome;
use App\Jobs\ProcessPlaylistAnalytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Medal;
use App\Models\Playlist;
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

    public function test_longest_game_sticks_to_proper_category(): void
    {
        // Arrange
        $playlist1 = Playlist::factory()
            ->create([
                'name' => 'Playlist 1',
            ]);

        $playlist2 = Playlist::factory()
            ->create([
                'name' => 'Playlist 2',
            ]);

        Game::factory()
            ->playlist($playlist1)
            ->create([
                'duration_seconds' => 100,
            ]);

        Game::factory()
            ->playlist($playlist2)
            ->create([
                'duration_seconds' => 200,
            ]);

        // Act
        ProcessPlaylistAnalytic::dispatchSync($playlist1);

        // Assert
        $this->assertDatabaseHas(PlaylistAnalytic::class, [
            'playlist_id' => $playlist1->id,
            'key' => AnalyticKey::LONGEST_MATCHMAKING_GAME->value,
            'value' => 100,
        ]);

        $this->assertDatabaseMissing(PlaylistAnalytic::class, [
            'playlist_id' => $playlist2->id,
            'key' => AnalyticKey::LONGEST_MATCHMAKING_GAME->value,
        ]);
    }

    #[DataProvider('playlistDataProvider')]
    public function test_processing_each_category(AnalyticInterface $analyticClass): void
    {
        // Arrange
        Medal::factory()
            ->create([
                'id' => 1512363953,
                'name' => 'Most Perfects',
            ]);

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
