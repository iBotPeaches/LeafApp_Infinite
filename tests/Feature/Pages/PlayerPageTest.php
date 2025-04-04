<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Enums\CompetitiveMode;
use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Csr;
use App\Models\Player;
use App\Models\PlayerBan;
use App\Models\Playlist;
use App\Models\Rank;
use App\Models\ServiceRecord;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\BanSummary\MockBanSummaryService;
use Tests\TestCase;

class PlayerPageTest extends TestCase
{
    #[DataProvider('gamertagDataProvider')]
    public function test_loading_player_matches_page(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        $player = Player::factory()
            ->createOne([
                'gamertag' => $gamertag,
                'xp' => 1000,
                'rank_id' => 12,
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

    public function test_profile_loading_if_no_appearance(): void
    {
        // Arrange
        $gamertag = 'TESTGAMERTAG';

        $mockAppearanceResponse = (new MockAppearanceService)->success($gamertag);
        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag));

        // Assert
        $response->assertOk();
    }

    public function test_linking_profile(): void
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

    public function test_unlinking_profile(): void
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

    public function test_check_for_ban(): void
    {
        // Arrange
        /** @var Player $player */
        $player = Player::factory()->createOne();

        $mockBanCheckResponse = (new MockBanSummaryService)->banned($player->gamertag);
        Http::fakeSequence()
            ->push($mockBanCheckResponse, Response::HTTP_OK);

        $user = User::factory()->createOne();

        $this->actingAs($user);

        // Act
        $response = $this->get('/player/'.urlencode($player->gamertag).'/ban-check');

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_cheater' => true,
        ]);
    }

    public function test_check_for_no_ban(): void
    {
        // Arrange
        /** @var Player $player */
        $player = Player::factory()->createOne();

        $mockBanCheckResponse = (new MockBanSummaryService)->unbanned($player->gamertag);
        Http::fakeSequence()
            ->push($mockBanCheckResponse, Response::HTTP_OK);

        $user = User::factory()->createOne();

        $this->actingAs($user);

        // Act
        $response = $this->get('/player/'.urlencode($player->gamertag).'/ban-check');

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_cheater' => false,
        ]);
    }

    #[DataProvider('gamertagDataProvider')]
    public function test_loading_player_overview_page(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Player::factory()
            ->createOne([
                'gamertag' => $gamertag,
                'is_throttled' => true,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag).'/overview');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Updates Throttled');
        $response->assertSeeLivewire('player-overview-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    public function test_loading_player_matches_page_with_playlist_filter(): void
    {
        // Arrange
        Http::fake();

        $player = Player::factory()->createOne([
            'gamertag' => 'gamertag',
        ]);

        $playlist = Playlist::factory()->createOne();

        // Act
        $response = $this->get('/player/'.$player->url_safe_gamertag.'/matches?playlist='.$playlist->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('game-history-table');
        $response->assertSeeLivewire('update-player-panel');
    }

    public function test_loading_player_overview_page_with_medal_data(): void
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
        $response->assertSeeLivewire('player-overview-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    public function test_loading_player_overview_page_as_banned_user(): void
    {
        // Arrange
        Http::fake();

        /** @var Player $player */
        $player = Player::factory()->createOne();
        PlayerBan::factory()->createOne([
            'player_id' => $player->id,
        ]);

        // Act
        $response = $this->get('/player/'.urlencode($player->gamertag).'/overview');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('player-overview-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    #[DataProvider('gamertagDataProvider')]
    public function test_loading_player_overview_page_as_private_player(string $gamertag): void
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
        $response->assertSeeLivewire('player-overview-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    #[DataProvider('gamertagDataProvider')]
    public function test_loading_player_modes_page(string $gamertag): void
    {
        // Arrange
        Http::fake();

        Player::factory()
            ->createOne([
                'gamertag' => $gamertag,
            ]);

        // Act
        $response = $this->get('/player/'.urlencode($gamertag).'/modes');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeLivewire('mode-page');
        $response->assertSeeLivewire('update-player-panel');
    }

    #[DataProvider('gamertagDataProvider')]
    public function test_loading_player_competitive_page(string $gamertag): void
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

    public static function gamertagDataProvider(): array
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
