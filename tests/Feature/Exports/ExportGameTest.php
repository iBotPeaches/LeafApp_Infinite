<?php
declare(strict_types=1);

namespace Tests\Feature\Exports;

use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExportGameTest extends TestCase
{
    public function testExportGame(): void
    {
        // Arrange
        Http::fake();

        $game = Game::factory()
            ->has(GamePlayer::factory()->count(8), 'players')
            ->createOne();

        // Act
        $response = $this->get(route('gameCsv', [$game]));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertIsString($response->streamedContent());
    }
}
