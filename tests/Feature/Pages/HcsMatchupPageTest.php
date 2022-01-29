<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Matchup;
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

        $matchup = Matchup::factory()
            ->has(MatchupPlayer::factory())
            ->createOne();

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

        $matchup = Matchup::factory()->createOne();

        // Act
        $response = $this->get(route('matchup', [$matchup->championship, $matchup]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-matchup');
    }
}
