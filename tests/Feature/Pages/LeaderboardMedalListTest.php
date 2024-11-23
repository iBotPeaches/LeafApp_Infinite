<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\ServiceRecord;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LeaderboardMedalListTest extends TestCase
{
    public function test_loading_all_medals(): void
    {
        // Arrange
        ServiceRecord::factory()
            ->withMedals()
            ->create();

        // Act & Assert
        $response = $this->get('/leaderboards/medal');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('medals-table');
    }
}
