<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\Mode;
use App\Enums\Outcome;
use App\Jobs\ProcessAnalytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\HasAnalyticDataProvider;

class ProcessAnalyticTest extends TestCase
{
    use HasAnalyticDataProvider;
    use WithFaker;

    /** @dataProvider analyticDataProvider */
    public function testProcessingEachCategory(AnalyticInterface $analyticClass): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();
        ServiceRecord::factory()->createOne([
            'mode' => Mode::MATCHMADE_PVP,
            'season_number' => null,
            'total_matches' => 1102,
        ]);
        GamePlayer::factory()
            ->for(
                Game::factory()
                    ->forPlaylist(['is_ranked' => true])
                    ->forMap([])
            )
            ->createOne([
                'deaths' => 0,
                'outcome' => Outcome::LEFT,
            ]);

        // Act
        ProcessAnalytic::dispatchSync($analyticClass);

        // Assert
        $this->assertDatabaseHas('analytics', [
            'key' => $analyticClass->key(),
        ]);
    }
}
