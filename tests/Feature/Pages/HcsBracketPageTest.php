<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\Bracket;
use App\Models\Championship;
use App\Models\Matchup;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HcsBracketPageTest extends TestCase
{
    /** @dataProvider bracketDataProvider */
    public function testLoadingBracketPageWithMatchups(?int $round, ?string $bracket): void
    {
        // Arrange
        Http::fake();

        $championship = Championship::factory()
            ->has(Matchup::factory())
            ->createOne();

        // Act
        $response = $this->get(route('championship', [$championship, $bracket, $round]));

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

    public function bracketDataProvider(): array
    {
        return [
            [
                'round' => null,
                'group' => null
            ],
            [
                'round' => 1,
                'group' => Bracket::WINNERS
            ],
            [
                'round' => 2,
                'group' => Bracket::LOSERS
            ]
        ];
    }
}
