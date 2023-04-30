<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\Mode;
use App\Jobs\ProcessMedalAnalytic;
use App\Models\Medal;
use App\Models\ServiceRecord;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProcessMedalAnalyticTest extends TestCase
{
    use WithFaker;

    public function testProcessingMergedSeason(): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();
        $sr = ServiceRecord::factory()
            ->withMedals()
            ->createOne([
                'mode' => Mode::MATCHMADE_PVP,
                'season_number' => null,
                'season_key' => null,
                'total_matches' => 1102,
            ]);

        /** @var Medal $medal */
        $medal = Medal::query()->first();

        // Act
        ProcessMedalAnalytic::dispatchSync($medal);

        // Assert
        $this->assertDatabaseHas('medal_analytics', [
            'medal_id' => $medal->id,
            'season_id' => null
        ]);
    }
}
