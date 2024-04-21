<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\OverviewTab;
use App\Models\Overview;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OverviewPageTest extends TestCase
{
    public function testLoadingOverviewOnPageOverview(): void
    {
        // Arrange
        $overview = Overview::factory()->createOne();

        // Act & Assert
        $response = $this->get(route('overview', [$overview]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('overview-toggle-panel');
        $response->assertSeeLivewire('overview-card');
        $response->assertSeeLivewire('overview-overview');
    }

    public function testLoadingOverviewOnPageMatches(): void
    {
        // Arrange
        $overview = Overview::factory()->createOne();

        // Act & Assert
        $response = $this->get(route('overview', [$overview, OverviewTab::MATCHES]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('overview-toggle-panel');
        $response->assertSeeLivewire('overview-card');
        $response->assertSeeLivewire('overview-matches-table');
    }
}
