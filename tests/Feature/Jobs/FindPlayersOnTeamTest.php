<?php
declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Jobs\FindPlayersFromTeam;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\Xuid\MockXuidService;
use Tests\TestCase;

class FindPlayersOnTeamTest extends TestCase
{
    use WithFaker;

    public function testFindingPlayerFromTeam(): void
    {
        // Arrange
        $gamertag = $this->faker->word;
        $mockAppearanceResponse = (new MockAppearanceService())->success();
        $mockXuidResponse = (new MockXuidService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockXuidResponse, Response::HTTP_OK);


        $matchupTeam = MatchupTeam::factory()->createOne();
        $matchupPlayer = MatchupPlayer::factory()->createOne([
            'matchup_team_id' => $matchupTeam->id,
            'faceit_name' => $gamertag,
            'player_id' => null
        ]);

        // Act
        FindPlayersFromTeam::dispatchSync($matchupTeam);

        // Assert
        $this->assertDatabaseHas('players', [
            'emblem_url' => Arr::get($mockAppearanceResponse, 'data.emblem_url')
        ]);
    }
}
