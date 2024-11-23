<?php

declare(strict_types=1);

namespace Tests\Feature\Exports;

use App\Enums\Experience;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExportMatchHistoryTest extends TestCase
{
    public function test_export_game_records_as_matches(): void
    {
        // Arrange
        Http::fake();

        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->createOne([
                'player_id' => $player->id,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($player->gamertag).'/matches/csv/matches');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertIsString($response->streamedContent());
    }

    public function test_export_game_records_as_random(): void
    {
        // Arrange
        Http::fake();

        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->createOne([
                'player_id' => $player->id,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($player->gamertag).'/matches/csv/overview');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertIsString($response->streamedContent());
    }

    public function test_export_game_records_as_custom(): void
    {
        // Arrange
        Http::fake();

        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->for(Game::factory()->create([
                'is_lan' => null,
                'experience' => Experience::CUSTOM,
            ]))
            ->createOne([
                'player_id' => $player->id,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($player->gamertag).'/matches/csv/custom');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertIsString($response->streamedContent());
    }

    public function test_export_game_records_as_lan(): void
    {
        // Arrange
        Http::fake();

        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->for(Game::factory()->create([
                'is_lan' => true,
                'experience' => Experience::CUSTOM,
            ]))
            ->createOne([
                'player_id' => $player->id,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($player->gamertag).'/matches/csv/lan');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertIsString($response->streamedContent());
    }
}
