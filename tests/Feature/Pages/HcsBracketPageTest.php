<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Championship;
use App\Models\Matchup;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HcsBracketPageTest extends TestCase
{
    public function testLoadingBracketPageWithMatchups(): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()
            ->has(Matchup::factory())
            ->createOne();

        // Act
        $response = $this->get(route('championship', [$championship]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-bracket');
    }

    public function testLoadingBracketPageWithNoMatchups(): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()->createOne();

        // Act
        $response = $this->get(route('championship', [$championship]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('championship-bracket');
    }
}
