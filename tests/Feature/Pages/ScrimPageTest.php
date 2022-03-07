<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTeam;
use App\Models\Pivots\GameScrim;
use App\Models\Player;
use App\Models\Scrim;
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
            'is_complete' => true
        ]);
        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id
        ]);

        // Act
        $response = $this->get(route('scrim', $scrim));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('scrim-overview');
    }

    public function testLoadingScrimMatchesPageWithData(): void
    {
        // Arrange
        Http::fake();

        $scrim = Scrim::factory()->createOne([
            'is_complete' => true
        ]);
        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
            'game_id' => Game::factory()->has(GameTeam::factory(), 'teams')
        ]);

        // Act
        $response = $this->get(route('scrim', [
            $scrim,
            'matches'
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
            'is_complete' => true
        ]);
        GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
            'game_id' => Game::factory()
                ->has(GameTeam::factory()->has(GamePlayer::factory(), 'players'), 'teams')
        ]);

        // Act
        $response = $this->get(route('scrim', [
            $scrim,
            'players'
        ]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('scrim-players');
    }
}
