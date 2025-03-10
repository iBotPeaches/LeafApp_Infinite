<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Category;
use App\Models\Level;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Player;
use App\Models\Playlist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\TestCase;

class PullMatchupGameTest extends TestCase
{
    use WithFaker;

    public function test_valid_data_pull(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockHistoryResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyHistoryResponse = (new MockMatchesService)->empty($gamertag);

        Arr::set($mockHistoryResponse, 'data.0.details.playlist', null);

        Http::fakeSequence()
            ->push($mockHistoryResponse, Response::HTTP_OK)
            ->push($mockEmptyHistoryResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        /** @var Matchup $matchup */
        $matchup = Matchup::factory()
            ->has(
                MatchupTeam::factory()
                    ->has(
                        MatchupPlayer::factory()->for($player),
                        'faceitPlayers'
                    )
            )
            ->createOne();

        // Act & Assert
        $this
            ->artisan('app:pull-matchup', ['matchupId' => $matchup->faceit_id])
            ->assertExitCode(CommandAlias::SUCCESS);

        $this->assertCount(1, $matchup->games);
    }
}
