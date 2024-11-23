<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\ChampionshipType;
use App\Enums\Outcome;
use App\Models\Championship;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupGame;
use App\Models\Pivots\MatchupPlayer;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HcsMatchupPageTest extends TestCase
{
    #[DataProvider('groupDataProvider')]
    public function test_loading_matchup_page_with_team_and_players(int $group): void
    {
        // Arrange
        Http::fake();

        $matchup = Matchup::factory()
            ->createOne([
                'group' => $group,
            ]);

        $matchupTeam = MatchupTeam::factory()->createOne([
            'outcome' => Outcome::WIN,
            'matchup_id' => $matchup->id,
        ]);
        MatchupPlayer::factory()->createOne([
            'matchup_team_id' => $matchupTeam->id,
        ]);

        MatchupTeam::factory()->createOne([
            'matchup_id' => $matchup->id,
            'outcome' => Outcome::LOSS,
        ]);

        // Act
        $response = $this->get(route('matchup', [$matchup->championship, $matchup]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-matchup');
    }

    public function test_loading_matchup_page_for_ffa(): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()->createOne([
            'type' => ChampionshipType::STAGE,
        ]);

        $matchup = Matchup::factory()
            ->createOne([
                'championship_id' => $championship->id,
            ]);

        MatchupTeam::factory()->createOne([
            'outcome' => Outcome::WIN,
            'matchup_id' => $matchup->id,
        ]);

        MatchupTeam::factory()->createOne([
            'outcome' => Outcome::LOSS,
            'matchup_id' => $matchup->id,
        ]);

        $matchupGame = MatchupGame::factory()
            ->for(Game::factory()->has(GamePlayer::factory(), 'players'))
            ->createOne([
                'matchup_id' => $matchup->id,
            ]);

        // Act
        $response = $this->get(route('matchup', [$matchup->championship, $matchup]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-matchup');
    }

    public function test_loading_matchup_page_for_swiss(): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()->createOne([
            'type' => ChampionshipType::SWISS,
        ]);

        $matchup = Matchup::factory()
            ->createOne([
                'championship_id' => $championship->id,
            ]);

        MatchupTeam::factory()->createOne([
            'outcome' => Outcome::WIN,
            'matchup_id' => $matchup->id,
        ]);

        MatchupTeam::factory()->createOne([
            'outcome' => Outcome::LOSS,
            'matchup_id' => $matchup->id,
        ]);

        $matchupGame = MatchupGame::factory()
            ->for(Game::factory()->has(GamePlayer::factory(), 'players'))
            ->createOne([
                'matchup_id' => $matchup->id,
            ]);

        // Act
        $response = $this->get(route('matchup', [$matchup->championship, $matchup]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-matchup');
    }

    public function test_loading_matchup_page_with_no_data(): void
    {
        // Arrange
        Http::fake();

        $matchupTeam = MatchupTeam::factory()->createOne([
            'outcome' => Outcome::WIN,
        ]);
        $matchup = $matchupTeam->matchup;
        MatchupTeam::factory()
            ->bye()
            ->createOne([
                'matchup_id' => $matchup->id,
                'outcome' => Outcome::LOSS,
            ]);

        // Act
        $response = $this->get(route('matchup', [$matchup->championship, $matchup]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-matchup');
    }

    public static function groupDataProvider(): array
    {
        return [
            [
                'group' => 1,
            ],
            [
                'group' => 2,
            ],
            [
                'group' => 3,
            ],
        ];
    }
}
