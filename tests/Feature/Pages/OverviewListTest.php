<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Overview;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OverviewListTest extends TestCase
{
    public function testLoadingOverviews(): void
    {
        // Arrange
        Overview::factory()->createOne();

        // Act & Assert
        $response = $this->get('/overviews');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('overviews-table');
    }
}
