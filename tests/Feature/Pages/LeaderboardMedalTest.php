<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Medal;
use App\Models\ServiceRecord;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LeaderboardMedalTest extends TestCase
{
    public function testLoadingAllMedals(): void
    {
        // Arrange
        /** @var ServiceRecord $serviceRecord */
        $serviceRecord = ServiceRecord::factory()
            ->withMedals()
            ->create();

        // Acts & Assert
        foreach ($serviceRecord->hydrated_medals as $medal) {
            /** @var Medal $medal */
            $response = $this->get('/leaderboards/medal/' . $medal->id);

            // Assert
            $response->assertStatus(Response::HTTP_OK);
            $response->assertSeeLivewire('player-toggle-panel');
            $response->assertSeeLivewire('medals-leaderboard');
        }
    }
}
