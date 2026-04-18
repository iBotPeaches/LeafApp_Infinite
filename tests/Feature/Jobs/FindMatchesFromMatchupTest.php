<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Jobs\FindMatchesFromMatchup;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Player;
use App\Services\DotApi\InfiniteInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class FindMatchesFromMatchupTest extends TestCase
{
    use WithFaker;

    public function test_dot_api_marked_as_disabled(): void
    {
        // Arrange
        Config::set('services.dotapi.disabled', true);

        Http::preventStrayRequests();

        $matchup = Matchup::factory()->createOne();

        // Act
        FindMatchesFromMatchup::dispatchSync($matchup);

        // Assert
        Http::assertNothingSent();
    }

    public function test_finds_games_happening_the_day_after_matchup_start(): void
    {
        // Arrange
        $this->app->instance(
            InfiniteInterface::class,
            Mockery::mock(InfiniteInterface::class)
                ->shouldReceive('matches')
                ->once()
                ->andReturn(new EloquentCollection)
                ->getMock()
        );

        $startedAt = Carbon::parse('2026-01-15 18:30:00');
        $matchup = Matchup::factory()->createOne([
            'started_at' => $startedAt,
        ]);
        $player = Player::factory()->createOne();
        $matchupTeam = MatchupTeam::factory()->createOne([
            'matchup_id' => $matchup->id,
        ]);
        MatchupPlayer::factory()->createOne([
            'matchup_team_id' => $matchupTeam->id,
            'player_id' => $player->id,
        ]);

        $nextDayGame = Game::factory()->createOne([
            'playlist_id' => null,
            'occurred_at' => $startedAt->copy()->addDay(),
        ]);
        GamePlayer::factory()->createOne([
            'game_id' => $nextDayGame->id,
            'player_id' => $player->id,
        ]);

        // Act
        FindMatchesFromMatchup::dispatchSync($matchup);

        // Assert
        $this->assertDatabaseHas('matchup_game', [
            'matchup_id' => $matchup->id,
            'game_id' => $nextDayGame->id,
        ]);
    }
}
