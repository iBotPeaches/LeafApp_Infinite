<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Medal;
use App\Models\MedalAnalytic;
use App\Models\Season;
use App\Models\ServiceRecord;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LeaderboardMedalTest extends TestCase
{
    public function testLoadingAllMedals(): void
    {
        // Arrange
        /** @var Season $season */
        $season = Season::factory()->createOne();

        /** @var ServiceRecord $serviceRecord */
        $serviceRecord = ServiceRecord::factory()
            ->withMedals()
            ->create([
                'season_key' => $season->key,
                'season_number' => $season->season_id,
            ]);

        /** @var Medal $medal */
        $medal = Medal::query()->first();

        MedalAnalytic::factory()->createOne([
            'medal_id' => $medal->id,
            'mode' => $serviceRecord->mode,
            'player_id' => $serviceRecord->player_id,
            'season_id' => $season->id,
        ]);

        ModeSession::set((int) $serviceRecord->mode->value);
        SeasonSession::set($season->key);

        // Acts & Assert
        foreach ($serviceRecord->hydrated_medals as $medal) {
            /** @var Medal $medal */
            $response = $this->get('/leaderboards/medal/'.$medal->id);

            // Assert
            $response->assertStatus(Response::HTTP_OK);
            $response->assertSeeLivewire('player-toggle-panel');
            $response->assertSeeLivewire('medals-leaderboard');
        }
    }

    public function testLoadingAllMedalsAsMergedSeason(): void
    {
        // Arrange
        /** @var ServiceRecord $serviceRecord */
        $serviceRecord = ServiceRecord::factory()
            ->withMedals()
            ->create([
                'season_key' => null,
                'season_number' => null,
            ]);

        ModeSession::set((int) $serviceRecord->mode->value);
        SeasonSession::set(SeasonSession::$allSeasonKey);

        /** @var Medal $medal */
        $medal = Medal::query()->first();

        MedalAnalytic::factory()->createOne([
            'medal_id' => $medal->id,
            'mode' => $serviceRecord->mode,
            'player_id' => $serviceRecord->player_id,
            'season_id' => null,
        ]);

        // Acts & Assert
        foreach ($serviceRecord->hydrated_medals as $medal) {
            /** @var Medal $medal */
            $response = $this->get('/leaderboards/medal/'.$medal->id);

            // Assert
            $response->assertStatus(Response::HTTP_OK);
            $response->assertSeeLivewire('player-toggle-panel');
            $response->assertSeeLivewire('medals-leaderboard');
        }
    }
}
