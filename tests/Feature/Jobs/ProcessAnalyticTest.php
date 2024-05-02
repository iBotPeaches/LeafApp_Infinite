<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\Mode;
use App\Enums\Outcome;
use App\Jobs\ProcessAnalytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\OverviewStat;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\Stats\MostGamesPlayedServiceRecord;
use App\Support\Analytics\Stats\MostXpPlayer;
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
    public function testProcessingEachCategory(AnalyticInterface $analyticClass): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();
        ServiceRecord::factory()->createOne([
            'mode' => Mode::MATCHMADE_PVP,
            'season_key' => null,
            'total_matches' => 1102,
        ]);
        GamePlayer::factory()
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

    public function testProcessingXpPlayerWithDuplicateXpValues(): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();
        Player::factory()
            ->sequence(
                ['xp' => 1111],
                ['xp' => 1111],
            )
            ->count(2)
            ->create();

        // Act
        $analyticClass = new MostXpPlayer();
        ProcessAnalytic::dispatchSync($analyticClass);

        // Assert
        $this->assertDatabaseHas('analytics', [
            'key' => $analyticClass->key(),
        ]);
    }

    public function testProcessingMostGamesPlayedWithDuplicateValues(): void
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
        $analyticClass = new MostGamesPlayedServiceRecord();
        ProcessAnalytic::dispatchSync($analyticClass);

        // Assert
        $this->assertDatabaseHas('analytics', [
            'key' => $analyticClass->key(),
        ]);
    }
}
