<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTeam;
use App\Models\Pivots\GameScrim;
use App\Models\Scrim;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ScrimPageTest extends TestCase
{
    public function testLoadingScrimOverviewPageWithData(): void
    {
        // Arrange
        Http::fake();

        $scrim = Scrim::factory()->createOne([
            'is_complete' => true,
        ]);
        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
        ]);

        // Act
        $response = $this->get(route('scrim', $scrim));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('scrim-overview');
    }

    public function testLoadingScrimMatchesPageWithDataAsFfa(): void
    {
        // Arrange
        Http::fake();

        $scrim = Scrim::factory()->createOne([
            'is_complete' => true,
        ]);
        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
            'game_id' => Game::factory()->state(['is_ffa' => true])->has(GameTeam::factory(), 'teams'),
        ]);

        // Act
        $response = $this->get(route('scrim', [
            $scrim,
            'matches',
        ]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('scrim-matches');
    }

    public function testLoadingScrimMatchesPageWithDataAsTeam(): void
    {
        // Arrange
        Http::fake();

        $scrim = Scrim::factory()->createOne([
            'is_complete' => true,
        ]);
        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
            'game_id' => Game::factory()->state(['is_ffa' => false])->has(GameTeam::factory(), 'teams'),
        ]);

        // Act
        $response = $this->get(route('scrim', [
            $scrim,
            'matches',
        ]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('scrim-matches');
    }

    public function testLoadingScrimPlayersPageWithData(): void
    {
        // Arrange
        Http::fake();

        $scrim = Scrim::factory()->createOne([
            'is_complete' => true,
        ]);

        $game = Game::factory()
            ->createOne([
                'version' => config('services.autocode.version'),
                'was_pulled' => true,
            ]);

        $gamePlayers = GamePlayer::factory()
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

        $player = $gamePlayers->first()->player;

        $game2 = Game::factory()
            ->createOne([
                'version' => config('services.autocode.version'),
                'was_pulled' => true,
            ]);

        GamePlayer::factory()
            ->for($game2)
            ->withMedals()
            ->state(new Sequence(
                ['player_id' => $player->id],
            ))
            ->createOne();

        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
            'game_id' => $game->id,
        ]);

        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
            'game_id' => $game2->id,
        ]);

        // Act
        $response = $this->get(route('scrim', [
            $scrim,
            'players',
        ]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('scrim-players');
    }
}
