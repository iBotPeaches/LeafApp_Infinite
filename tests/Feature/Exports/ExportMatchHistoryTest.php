<?php
declare(strict_types=1);

namespace Tests\Feature\Exports;

use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExportMatchHistoryTest extends TestCase
{
    public function testExportGameRecords(): void
    {
        // Arrange
        Http::fake();

        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->createOne([
                'player_id' => $player->id
            ]);

        // Act
        $response = $this->get('/player/' . urlencode($player->gamertag) . '/matches/csv');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertIsString($response->streamedContent());
    }
}
