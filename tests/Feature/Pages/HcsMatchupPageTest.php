<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\Outcome;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HcsMatchupPageTest extends TestCase
{
    public function testLoadingMatchupPageWithTeamAndPlayers(): void
    {
        // Arrange
        Http::fake();

        $matchupTeam = MatchupTeam::factory()->createOne([
            'outcome' => Outcome::WIN
        ]);
        MatchupPlayer::factory()->createOne([
            'matchup_team_id' => $matchupTeam->id
        ]);

        $matchup = $matchupTeam->matchup;
        MatchupTeam::factory()->createOne([
            'matchup_id' => $matchup->id,
            'outcome' => Outcome::LOSS
        ]);

        // Act
        $response = $this->get(route('matchup', [$matchup->championship, $matchup]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-matchup');
    }

    public function testLoadingMatchupPageWithNoData(): void
    {
        // Arrange
        Http::fake();

        $matchupTeam = MatchupTeam::factory()->createOne([
            'outcome' => Outcome::WIN
        ]);
        $matchup = $matchupTeam->matchup;
        MatchupTeam::factory()
            ->bye()
            ->createOne([
                'matchup_id' => $matchup->id,
                'outcome' => Outcome::LOSS
            ]);

        // Act
        $response = $this->get(route('matchup', [$matchup->championship, $matchup]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-matchup');
    }
}
