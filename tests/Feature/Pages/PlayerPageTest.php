<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\CompetitiveMode;
use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Csr;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\TestCase;

class PlayerPageTest extends TestCase
{
    /** @dataProvider gamertagDataProvider */
    public function testLoadingPlayerMatchesPage(string $gamertag): void
    {
        // Arrange
        Http::fake();

        $player = Player::factory()
            ->createOne([
                'gamertag' => $gamertag,
            ]);

        $user = User::factory()
            ->hasPlayer($player)
            ->createOne();

        $this->actingAs($user);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag).'/matches');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-history-table');
        $response->assertSeeLivewire('update-player-panel');
    }

    public function testProfileLoadingIfNoAppearance(): void
    {
        // Arrange
        $gamertag = 'TESTGAMERTAG';

        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag));

        // Assert
        $response->assertOk();
    }

    public function testLinkingProfile(): void
    {
        // Arrange
        Http::fake();

        /** @var Player $player */
        $player = Player::factory()->createOne();
        $user = User::factory()
            ->hasPlayer($player)
            ->createOne();

        $this->actingAs($user);

        // Act
        $response = $this->post('/player/'.urlencode($player->gamertag).'/link');

        // Assert
        $response->assertRedirect();
    }

    public function testUnlinkingProfile(): void
    {
        // Arrange
        Http::fake();

        /** @var Player $player */
        $player = Player::factory()->createOne();
        $user = User::factory()
            ->hasPlayer($player)
            ->createOne();

        $this->actingAs($user);

        // Act
        $response = $this->post('/player/'.urlencode($player->gamertag).'/unlink');

        // Assert
        $response->assertRedirect();
    }

    /** @dataProvider gamertagDataProvider */
    public function testLoadingPlayerOverviewPage(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Player::factory()
            ->createOne([
                'gamertag' => $gamertag,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag).'/overview');

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
        $response = $this->get('/player/'.urlencode($serviceRecord->player->gamertag).'/overview');

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
                'is_private' => true,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag).'/overview');

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
            ->has(Csr::factory()->state(function () {
                return [
                    'queue' => Queue::OPEN,
                    'mode' => CompetitiveMode::SEASON,
                    'input' => Input::CROSSPLAY,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1450,
                    'csr' => 1499,
                ];
            }))
            ->has(Csr::factory()->state(function () {
                return [
                    'queue' => Queue::OPEN,
                    'mode' => CompetitiveMode::ALL_TIME,
                    'season' => null,
                    'input' => Input::CROSSPLAY,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1450,
                    'csr' => 1499,
                ];
            }))
            ->createOne([
                'gamertag' => $gamertag,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag).'/competitive');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('competitive-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    public function gamertagDataProvider(): array
    {
        return [
            'spaces' => [
                'gamertag' => 'gamer tag',
            ],
            'no spaces' => [
                'gamertag' => 'gamertag',
            ],
        ];
    }
}
