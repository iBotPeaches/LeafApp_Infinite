<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\UpdatePlayerPanel;

use App\Enums\CompetitiveMode;
use App\Enums\Input;
use App\Enums\PlayerTab;
use App\Http\Livewire\UpdatePlayerPanel;
use App\Jobs\PullAppearance;
use App\Jobs\PullCompetitive;
use App\Jobs\PullMatchHistory;
use App\Jobs\PullMmr;
use App\Jobs\PullServiceRecord;
use App\Models\Csr;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Player;
use App\Services\Autocode\Enums\Mode;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\Matches\MockMatchService;
use Tests\Mocks\Mmr\MockMmrService;
use Tests\Mocks\ServiceRecord\MockServiceRecordService;
use Tests\Mocks\Xuid\MockXuidService;
use Tests\TestCase;

class ValidPlayerUpdateTest extends TestCase
{
    use WithFaker;

    public function testAutomaticallyMarkedPrivateIfInvalidRecord(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
            PullCompetitive::class,
            PullMatchHistory::class,
            PullMmr::class
        ]);
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        // Set values into responses that "fake" a private account.
        Arr::set($mockServiceResponse, 'data.privacy.public', false);

        Http::fakeSequence()
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!');

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_private' => true
        ]);

        Bus::assertDispatched(PullAppearance::class);
        Bus::assertDispatched(PullCompetitive::class);
        Bus::assertDispatchedTimes(PullMatchHistory::class, 2);
        Bus::assertDispatched(PullMmr::class);
    }

    public function testAutomaticallySkippingServiceRecordIfUnplayedSeason(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
            PullCompetitive::class,
            PullMatchHistory::class,
            PullMmr::class
        ]);
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockServiceResponse = (new MockServiceRecordService())->error403();

        Http::fakeSequence()
            ->push($mockServiceResponse, Response::HTTP_FORBIDDEN);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!');

        Bus::assertDispatched(PullAppearance::class);
        Bus::assertDispatched(PullCompetitive::class);
        Bus::assertDispatchedTimes(PullMatchHistory::class, 2);
        Bus::assertDispatched(PullMmr::class);
    }

    public function testSkippingMmrIfApiIsReturningNull(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
            PullMatchHistory::class,
            PullServiceRecord::class,
        ]);
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMmrResponse = (new MockMmrService())->empty($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK);

        /** @var Player $player */
        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
            'mmr' => 1234,
            'mmr_game_id' => Game::factory()
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::COMPETITIVE,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertEmittedTo('competitive-page', '$refresh');

        Bus::assertDispatched(PullAppearance::class);
        Bus::assertDispatchedTimes(PullMatchHistory::class, 2);
        Bus::assertDispatched(PullServiceRecord::class);

        $player->refresh();
        $this->assertEquals(1234, $player->mmr);
        $this->assertNotNull($player->mmr_game_id);
    }

    public function testAutomaticallyPullingXuidIfMissing(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);
        $mockXuidResponse = (new MockXuidService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockXuidResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
            'xuid' => null
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!');

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'xuid' => Arr::get($mockXuidResponse, 'xuid')
        ]);

        Bus::assertDispatched(PullAppearance::class);
    }

    public function testAutomaticallyRemovingOldAgentIfXuidMoved(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class
        ]);

        $xuid = $this->faker->numerify('################');
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);
        $mockXuidResponse = (new MockXuidService())->success($gamertag, $xuid);

        Http::fakeSequence()
            ->push($mockXuidResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $oldPlayer = Player::factory()->createOne([
            'gamertag' => $gamertag . 'old',
            'xuid' => $xuid
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
            'xuid' => null
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!');

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'xuid' => Arr::get($mockXuidResponse, 'xuid')
        ]);

        $this->assertDatabaseMissing('players', [
            'id' => $oldPlayer->id
        ]);

        Bus::assertDispatched(PullAppearance::class);
    }

    public function testAutomaticallyUnmarkedPrivateIfValidRecord(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class
        ]);
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()
            ->has(Csr::factory()->state(function () {
                return [
                    'queue' => \App\Enums\Queue::OPEN,
                    'mode' => CompetitiveMode::ALL_TIME,
                    'season' => null,
                    'input' => Input::CROSSPLAY,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1500,
                    'csr' => 9999
                ];
            }))
            ->createOne([
                'gamertag' => $gamertag,
                'is_private' => true
            ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!');

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_private' => false
        ]);

        Bus::assertDispatched(PullAppearance::class);
    }

    public function testAutomaticallyDeferNextPagesIfGamesAlreadyLoaded(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
            PullCompetitive::class,
            PullMatchHistory::class,
            PullMmr::class,
            PullServiceRecord::class
        ]);
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        ModeSession::set(\App\Enums\Mode::MATCHMADE_RANKED);

        Http::fakeSequence()
            ->push($mockMatchesResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        $gamePlayer = GamePlayer::factory()
            ->createOne([
                'game_id' => Game::factory()->state([
                    'uuid' => Arr::get($mockMatchesResponse, 'data.matches.1.id')
                ]),
                'player_id' => $player->id
            ]);

        $player->last_game_id_pulled = $gamePlayer->game_id;
        $player->saveOrFail();

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::MATCHES,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertEmitted('$refresh');

        Bus::assertDispatched(PullAppearance::class);
        Bus::assertDispatched(PullMatchHistory::class, function (PullMatchHistory $job) {
            return Mode::CUSTOM()->is($job->mode);
        });
        Bus::assertDispatched(PullMmr::class);
        Bus::assertDispatched(PullServiceRecord::class);
    }

    public function testInitialPageLoadDeferredFromApiCalls(): void
    {
        // Arrange
        Http::fake();
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => false
        ])
            ->call('render')
            ->assertViewHas('color', 'is-info')
            ->assertViewHas('message', 'Checking for updated stats.');

        Http::assertNothingSent();
    }

    public function testPageLoadDeferredFromApiCalls(): void
    {
        // Arrange
        Http::fake();
        $player = Player::factory()->createOne();

        $cacheKey = 'player-profile-' . $player->id . SeasonSession::get() . md5($player->gamertag);
        Cache::put($cacheKey, true);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => false
        ])
            ->call('render')
            ->assertViewHas('color', 'is-dark')
            ->assertViewHas('message', 'Profile was recently updated (or updating). Check back soon.');

        Http::assertNothingSent();
    }

    public function testValidResponseFromAllHaloDotApiServicesAsFaceItPlayer(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        $mockLanMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanMatchesResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function testValidResponseFromAllHaloDotApiServicesAsOverview(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertEmittedTo('overview-page', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function testValidResponseFromAllHaloDotApiServicesAsCompetitive(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::COMPETITIVE,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertEmittedTo('competitive-page', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function testValidResponseFromAllHaloDotApiServicesAsMatches(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::MATCHES,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertEmittedTo('game-history-table', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function testValidResponseFromAllHaloDotApiServicesAsCustomMatches(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::CUSTOM,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertEmittedTo('game-custom-history-table', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function testValidResponseFromAllHaloDotApiServicesAsLanMatches(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::LAN,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertEmittedTo('game-lan-history-table', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }
}
