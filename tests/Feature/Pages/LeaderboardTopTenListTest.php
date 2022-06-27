<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Analytic;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LeaderboardTopTenListTest extends TestCase
{
    public function testLoadingAllAnalytics(): void
    {
        // Arrange
        Analytic::factory()
            ->create();

        // Act & Assert
        $response = $this->get('/leaderboards/top-ten');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('top-ten-table');
    }
}
