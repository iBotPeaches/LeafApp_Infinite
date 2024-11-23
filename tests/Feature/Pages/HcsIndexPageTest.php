<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Championship;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HcsIndexPageTest extends TestCase
{
    public function test_loading_championship_page_with_data(): void
    {
        // Arrange
        Http::fake();

        Championship::factory()->createOne();

        // Act
        $response = $this->get(route('championships'));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championships-table');
    }

    public function test_loading_championship_page_with_no_data(): void
    {
        // Arrange
        Http::fake();

        // Act
        $response = $this->get(route('championships'));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
