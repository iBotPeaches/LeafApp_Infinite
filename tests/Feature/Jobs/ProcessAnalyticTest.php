<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\Mode;
use App\Enums\Outcome;
use App\Jobs\ProcessAnalytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Medal;
use App\Models\OverviewStat;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\Stats\MostGamesPlayedServiceRecord;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\HasAnalyticDataProvider;

class ProcessAnalyticTest extends TestCase
{
    use HasAnalyticDataProvider;
    use WithFaker;

    #[DataProvider('analyticDataProvider')]
    public function test_processing_each_category(AnalyticInterface $analyticClass): void
    {
        // Arrange
        Medal::factory()
            ->create([
                'id' => 1512363953,
                'name' => 'Most Perfects',
            ]);

        Http::fake()->preventStrayRequests();
        ServiceRecord::factory()->createOne([
            'mode' => Mode::MATCHMADE_PVP,
            'season_key' => null,
            'total_matches' => 1102,
        ]);
        GamePlayer::factory()
            ->withMedals()
            ->for(
                Game::factory()
                    ->forPlaylist(['is_ranked' => true])
                    ->forMap([])
            )
            ->sequence(
                ['deaths' => 0, 'outcome' => Outcome::LEFT],
                ['deaths' => 0, 'outcome' => Outcome::WIN],
            )
            ->count(2)
            ->create();

        GamePlayer::factory()
            ->withMedals()
            ->for(
                Game::factory()
                    ->forPlaylist(['is_ranked' => false])
                    ->forMap([])
            )
            ->sequence(
                ['deaths' => 0, 'outcome' => Outcome::LEFT],
                ['deaths' => 0, 'outcome' => Outcome::WIN],
            )
            ->count(2)
            ->create();

        OverviewStat::factory()
            ->sequence(
                ['total_dnf' => 1, 'total_players' => 2],
                ['total_dnf' => 1, 'total_players' => 2],
            )
            ->count(2)
            ->create();

        // Act
        ProcessAnalytic::dispatchSync($analyticClass);

        // Assert
        $this->assertDatabaseHas('analytics', [
            'key' => $analyticClass->key(),
        ]);
    }

    public function test_processing_most_games_played_with_duplicate_values(): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();
        ServiceRecord::factory()
            ->count(2)
            ->create([
                'mode' => Mode::MATCHMADE_PVP,
                'season_key' => null,
                'total_matches' => 1102,
            ]);

        // Act
        $analyticClass = new MostGamesPlayedServiceRecord;
        ProcessAnalytic::dispatchSync($analyticClass);

        // Assert
        $this->assertDatabaseHas('analytics', [
            'key' => $analyticClass->key(),
        ]);
    }
}
