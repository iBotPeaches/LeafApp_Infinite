<?php

declare(strict_types=1);

namespace Tests\Feature\Exports;

use App\Models\GamePlayer;
use App\Models\Pivots\GameScrim;
use App\Models\Scrim;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExportScrimPlayersTest extends TestCase
{
    public function test_export_scrim_players(): void
    {
        // Arrange
        Http::fake();

        $scrim = Scrim::factory()->createOne([
            'is_complete' => true,
        ]);
        $gameScrim = GameScrim::factory()->createOne([
            'scrim_id' => $scrim->id,
        ]);
        GamePlayer::factory()->createOne([
            'game_id' => $gameScrim->game_id,
        ]);

        // Act
        $response = $this->get('/scrims/'.$scrim->id.'/players/csv');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertIsString($response->streamedContent());
    }
}
