<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Player;
use App\Models\ServiceRecord;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PlayerPageTest extends TestCase
{
    /** @dataProvider gamertagDataProvider */
    public function testLoadingPlayerMatchesPage(string $gamertag): void
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

    /** @dataProvider gamertagDataProvider */
    public function testLoadingPlayerOverviewPage(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Player::factory()
            ->createOne([
                'gamertag' => $gamertag
            ]);

        // Act
        $response = $this->get('/player/' . urlencode($gamertag) . '/overview');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('overview-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    public function testLoadingPlayerOverviewPageWithMedalData(): void
    {
        // Arrange
        Http::fake();

        /** @var ServiceRecord $serviceRecord */
        $serviceRecord = ServiceRecord::factory()
            ->withMedals()
            ->create();

        // Act
        $response = $this->get('/player/' . urlencode($serviceRecord->player->gamertag) . '/overview');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('overview-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    /** @dataProvider gamertagDataProvider */
    public function testLoadingPlayerOverviewPageAsPrivatePlayer(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Player::factory()
            ->createOne([
                'gamertag' => $gamertag,
                'is_private' => true
            ]);

        // Act
        $response = $this->get('/player/' . urlencode($gamertag) . '/overview');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('overview-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    /** @dataProvider gamertagDataProvider */
    public function testLoadingPlayerCompetitivePage(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Player::factory()
            ->createOne([
                'gamertag' => $gamertag
            ]);

        // Act
        $response = $this->get('/player/' . urlencode($gamertag) . '/competitive');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('competitive-page');
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
