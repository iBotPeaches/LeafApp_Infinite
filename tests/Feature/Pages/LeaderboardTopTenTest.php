<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\HasAnalyticDataProvider;

class LeaderboardTopTenTest extends TestCase
{
    use HasAnalyticDataProvider;

    #[DataProvider('analyticDataProvider')]
    public function testLoadingTopTen(AnalyticInterface $analyticClass): void
    {
        // Arrange
        Analytic::factory()
            ->create([
                'key' => $analyticClass->key(),
            ]);

        // Act & Assert
        $response = $this->get('/leaderboards/top-ten/'.$analyticClass->key());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('top-ten-leaderboard');
    }
}
