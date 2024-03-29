<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\Bracket;
use App\Enums\ChampionshipType;
use App\Enums\Outcome;
use App\Models\Championship;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupPlayer;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HcsBracketPageTest extends TestCase
{
    #[DataProvider('bracketDataProvider')]
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
                'outcome' => Outcome::LOSS,
            ]);

        MatchupTeam::factory()
            ->has(MatchupPlayer::factory(), 'faceitPlayers')
            ->createOne([
                'matchup_id' => $championship->matchups->first()->id,
                'points' => 2,
                'outcome' => Outcome::WIN,
            ]);

        // Act
        $response = $this->get(route('championship', [$championship, $bracket, $round]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-bracket');
    }

    public function testLoadingBracketPageWithScheduledMatchups(): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()
            ->has(Matchup::factory()->state(function () {
                return [
                    'started_at' => null,
                    'ended_at' => null,
                ];
            }))
            ->createOne();

        MatchupTeam::factory()
            ->has(MatchupPlayer::factory(), 'faceitPlayers')
            ->createOne([
                'matchup_id' => $championship->matchups->first()->id,
                'points' => 2,
            ]);

        // Act
        $response = $this->get(route('championship', [$championship, Bracket::WINNERS, 1]));

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
                'bracket' => null,
                'attributes' => [],
            ],
            [
                'round' => null,
                'bracket' => Bracket::RULES,
                'attributes' => [],
            ],
            [
                'round' => 1,
                'bracket' => Bracket::WINNERS,
                'attributes' => [],
            ],
            [
                'round' => 2,
                'bracket' => Bracket::LOSERS,
                'attributes' => [],
            ],
            [
                'round' => 2,
                'bracket' => Bracket::GRAND,
                'attributes' => [],
            ],
            [
                'round' => 1,
                'bracket' => Bracket::WINNERS,
                'attributes' => [
                    'type' => ChampionshipType::STAGE,
                ],
            ],
            [
                'round' => 2,
                'bracket' => Bracket::LOSERS,
                'attributes' => [
                    'type' => ChampionshipType::STAGE,
                ],
            ],
            [
                'round' => 2,
                'bracket' => Bracket::POOL_D,
                'attributes' => [
                    'type' => ChampionshipType::ROUND_ROBIN,
                ],
            ],
            [
                'round' => 1,
                'bracket' => Bracket::OTHER,
                'attributes' => [
                    'type' => ChampionshipType::ROUND_ROBIN,
                ],
            ],
            [
                'round' => 1,
                'bracket' => Bracket::OTHER,
                'attributes' => [
                    'type' => ChampionshipType::BRACKET,
                ],
            ],
            [
                'round' => 1,
                'bracket' => Bracket::MATCHES,
                'attributes' => [
                    'type' => ChampionshipType::SWISS,
                ],
            ],
            [
                'round' => 1,
                'bracket' => Bracket::SUMMARY,
                'attributes' => [
                    'type' => ChampionshipType::SWISS,
                ],
            ],
        ];
    }
}
