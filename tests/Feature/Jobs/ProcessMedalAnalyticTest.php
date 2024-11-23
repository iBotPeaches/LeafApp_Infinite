<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\Mode;
use App\Jobs\ProcessMedalAnalytic;
use App\Models\Medal;
use App\Models\Season;
use App\Models\ServiceRecord;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProcessMedalAnalyticTest extends TestCase
{
    use WithFaker;

    public function test_processing_merged_season(): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();
        ServiceRecord::factory()
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
            'mode' => Mode::MATCHMADE_PVP,
            'season_id' => null,
        ]);
    }

    public function test_processing_as_season(): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();
        ServiceRecord::factory()
            ->withMedals()
            ->createOne([
                'mode' => Mode::MATCHMADE_RANKED,
                'total_matches' => 1102,
            ]);

        /** @var Medal $medal */
        $medal = Medal::query()->first();

        /** @var Season $season */
        $season = Season::factory()->createOne([
            'key' => '1-1',
        ]);

        // Act
        ProcessMedalAnalytic::dispatchSync($medal, $season);

        // Assert
        $this->assertDatabaseHas('medal_analytics', [
            'medal_id' => $medal->id,
            'mode' => Mode::MATCHMADE_RANKED,
            'season_id' => $season->id,
        ]);
    }
}
