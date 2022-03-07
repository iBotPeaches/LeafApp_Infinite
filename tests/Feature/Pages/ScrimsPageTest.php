<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Scrim;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ScrimsPageTest extends TestCase
{
    public function testLoadingScrimsPageWithData(): void
    {
        // Arrange
        Http::fake();

        Scrim::factory()->createOne();

        // Act
        $response = $this->get(route('scrims'));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('scrims-table');
    }

    public function testLoadingScrimsPageWithNoData(): void
    {
        // Arrange
        Http::fake();

        // Act
        $response = $this->get(route('scrims'));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
