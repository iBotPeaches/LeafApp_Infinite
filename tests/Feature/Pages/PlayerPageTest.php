<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Player;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PlayerPageTest extends TestCase
{
    /** @dataProvider gamertagDataProvider */
    public function testLoadingPlayerPage(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Player::factory()
            ->createOne([
                'gamertag' => $gamertag
            ]);

        // Act
        $response = $this->get('/player/' . urlencode($gamertag) . '/matches');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-history-table');
        $response->assertSeeLivewire('update-player-panel');
    }

    public function gamertagDataProvider(): array
    {
        return [
            'spaces' => [
                'gamertag' => 'gamer tag'
            ],
            'no spaces' => [
                'gamertag' => 'gamertag'
            ]
        ];
    }
}
