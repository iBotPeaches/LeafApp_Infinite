<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\Bracket;
use App\Enums\ChampionshipType;
use App\Models\Championship;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HcsBracketPageTest extends TestCase
{
    /** @dataProvider bracketDataProvider */
    public function testLoadingBracketPageWithMatchups(?int $round, ?string $bracket, array $attributes): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()
            ->has(Matchup::factory())
            ->createOne($attributes);

        MatchupTeam::factory()
            ->has(MatchupPlayer::factory(), 'faceitPlayers')
            ->createOne([
                'matchup_id' => $championship->matchups->first()->id,
                'points' => 2,
            ]);

        // Act
        $response = $this->get(route('championship', [$championship, $bracket, $round]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-bracket');
    }

    public function testLoadingBracketPageWithNoMatchups(): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()->createOne();

        // Act
        $response = $this->get(route('championship', [$championship]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-bracket');
    }

    public static function bracketDataProvider(): array
    {
        return [
            [
                'round' => null,
                'group' => null,
                'attributes' => [],
            ],
            [
                'round' => 1,
                'group' => Bracket::WINNERS,
                'attributes' => [],
            ],
            [
                'round' => 2,
                'group' => Bracket::LOSERS,
                'attributes' => [],
            ],
            [
                'round' => 2,
                'group' => Bracket::GRAND,
                'attributes' => [],
            ],
            [
                'round' => 1,
                'group' => Bracket::WINNERS,
                'attributes' => [
                    'type' => ChampionshipType::STAGE,
                ],
            ],
            [
                'round' => 2,
                'group' => Bracket::LOSERS,
                'attributes' => [
                    'type' => ChampionshipType::STAGE,
                ],
            ],
            [
                'round' => 2,
                'group' => Bracket::POOL_D,
                'attributes' => [
                    'type' => ChampionshipType::ROUND_ROBIN,
                ],
            ],
            [
                'round' => 1,
                'group' => Bracket::OTHER,
                'attributes' => [
                    'type' => ChampionshipType::ROUND_ROBIN,
                ],
            ],
        ];
    }
}
