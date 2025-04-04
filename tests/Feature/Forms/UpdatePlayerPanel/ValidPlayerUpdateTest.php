<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\UpdatePlayerPanel;

use App\Enums\CompetitiveMode;
use App\Enums\Input;
use App\Enums\PlayerTab;
use App\Jobs\PullAppearance;
use App\Jobs\PullCompetitive;
use App\Jobs\PullMatchHistory;
use App\Jobs\PullServiceRecord;
use App\Livewire\UpdatePlayerPanel;
use App\Models\Category;
use App\Models\Csr;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Level;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Player;
use App\Models\Playlist;
use App\Models\Rank;
use App\Models\Season;
use App\Services\DotApi\Enums\Mode;
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
use Tests\Mocks\CareerRank\MockCareerRankService;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\ServiceRecord\MockServiceRecordService;
use Tests\Mocks\Xuid\MockXuidService;
use Tests\TestCase;

class ValidPlayerUpdateTest extends TestCase
{
    use WithFaker;

    public function test_automatically_marked_private_if_invalid_record(): void
    {
        // Arrange
        SeasonSession::set(SeasonSession::$allSeasonKey);
        Bus::fake([
            PullAppearance::class,
            PullCompetitive::class,
            PullMatchHistory::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        // Set values into responses that "fake" a private account.
        Arr::set($mockServiceResponse, 'data.time_played.seconds', 0);
        Arr::set($mockServiceResponse, 'data.stats.core.scores.personal', 0);

        Http::fakeSequence()
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
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
            'is_private' => true,
        ]);

        Bus::assertDispatched(PullAppearance::class);
        Bus::assertDispatched(PullCompetitive::class);
        Bus::assertDispatchedTimes(PullMatchHistory::class, 2);
    }

    public function test_automatically_skipping_service_record_if_unplayed_season(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
            PullCompetitive::class,
            PullMatchHistory::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockServiceResponse = (new MockServiceRecordService)->error403();
        $mockSuccessfulServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockServiceResponse, Response::HTTP_FORBIDDEN)
            ->push($mockSuccessfulServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
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
    }

    public function test_automatically_pulling_xuid_if_missing(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockXuidResponse = (new MockXuidService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockXuidResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        Season::factory()->createOne([
            'season_id' => config('services.dotapi.competitive.season'),
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
            'xuid' => null,
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
            'xuid' => Arr::get($mockXuidResponse, 'data.xuid'),
        ]);

        Bus::assertDispatched(PullAppearance::class);
    }

    public function test_automatically_marking_as_bot_farmer(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockXuidResponse = (new MockXuidService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockXuidResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        $playlist = Playlist::factory()->createOne([
            'uuid' => config('services.halo.playlists.bot-bootcamp'),
            'name' => 'Bot Bootcamp',
        ]);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        Season::factory()->createOne([
            'season_id' => config('services.dotapi.competitive.season'),
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
            'xuid' => null,
        ]);

        GamePlayer::factory()
            ->count(100)
            ->state([
                'player_id' => $player->id,
                'game_id' => Game::factory()->state([
                    'playlist_id' => $playlist->id,
                ]),
            ])
            ->create();

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
            'xuid' => Arr::get($mockXuidResponse, 'data.xuid'),
            'is_botfarmer' => true,
        ]);

        Bus::assertDispatched(PullAppearance::class);
    }

    public function test_automatically_removing_old_agent_if_xuid_moved(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);

        $xuid = $this->faker->numerify('################');
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockXuidResponse = (new MockXuidService)->success($gamertag, $xuid);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockXuidResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        $oldPlayer = Player::factory()->createOne([
            'gamertag' => $gamertag.'old',
            'xuid' => $xuid,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
            'xuid' => null,
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
            'xuid' => Arr::get($mockXuidResponse, 'data.xuid'),
        ]);

        $this->assertDatabaseMissing('players', [
            'id' => $oldPlayer->id,
        ]);

        Bus::assertDispatched(PullAppearance::class);
    }

    public function test_automatically_unmarked_private_if_valid_record(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        $playlist = Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        $player = Player::factory()
            ->has(Csr::factory()->state(function () use ($playlist) {
                return [
                    'playlist_id' => $playlist->id,
                    'queue' => \App\Enums\Queue::OPEN,
                    'mode' => CompetitiveMode::ALL_TIME,
                    'season' => null,
                    'input' => Input::CROSSPLAY,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1500,
                    'csr' => 9999,
                ];
            }))
            ->createOne([
                'gamertag' => $gamertag,
                'is_private' => true,
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
            'is_private' => false,
        ]);

        Bus::assertDispatched(PullAppearance::class);
    }

    public function test_automatically_defer_next_pages_if_games_already_loaded(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
            PullCompetitive::class,
            PullMatchHistory::class,
            PullServiceRecord::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);
        ModeSession::set(\App\Enums\Mode::MATCHMADE_RANKED);

        Http::fakeSequence()
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        $gamePlayer = GamePlayer::factory()
            ->createOne([
                'game_id' => Game::factory()->state([
                    'uuid' => Arr::get($mockMatchesResponse, 'data.1.id'),
                ]),
                'player_id' => $player->id,
            ]);

        $player->last_game_id_pulled = $gamePlayer->game_id;
        $player->save();

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::MATCHES,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatched('$refresh');

        Bus::assertDispatched(PullAppearance::class);
        Bus::assertDispatched(PullMatchHistory::class, function (PullMatchHistory $job) {
            return Mode::CUSTOM()->is($job->mode);
        });
        Bus::assertDispatched(PullServiceRecord::class);
    }

    public function test_initial_page_load_deferred_from_api_calls(): void
    {
        // Arrange
        Http::fake();
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => false,
        ])
            ->assertViewHas('color', 'is-info')
            ->assertViewHas('message', 'Checking for updated stats.');

        Http::assertNothingSent();
    }

    public function test_page_load_deferred_from_api_calls(): void
    {
        // Arrange
        Http::fake();
        $player = Player::factory()->createOne();

        $cacheKey = 'player-profile-'.$player->id.SeasonSession::get().md5($player->gamertag);
        Cache::put($cacheKey, true);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => false,
        ])
            ->assertViewHas('color', 'is-dark')
            ->assertViewHas('message', 'Profile was recently updated (or updating). Check back soon.');

        Http::assertNothingSent();
    }

    public function test_page_load_deferred_from_api_calls_as_older_season(): void
    {
        // Arrange
        Http::fake();
        SeasonSession::set('1-1');
        $player = Player::factory()->createOne();

        $cacheKey = 'player-profile-'.$player->id.SeasonSession::get().md5($player->gamertag);
        Cache::put($cacheKey, true);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => false,
        ])
            ->assertViewHas('color', 'is-dark')
            ->assertViewHas('message', 'Season has ended. No more stat updates allowed.');

        Http::assertNothingSent();
    }

    public function test_page_load_deferred_from_api_calls_as_throttled_user(): void
    {
        // Arrange
        Http::fake();
        SeasonSession::set('1-1');
        $player = Player::factory()->createOne([
            'is_throttled' => true,
        ]);

        $cacheKey = 'player-profile-'.$player->id.SeasonSession::get().md5($player->gamertag);
        Cache::put($cacheKey, true);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => false,
        ])
            ->assertViewHas('color', 'is-dark')
            ->assertViewHas('message', 'Player is throttled. Updates are delayed.');

        Http::assertNothingSent();
    }

    public function test_valid_response_from_all_dot_api_services_as_face_it_player(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanMatchesResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function test_valid_response_from_all_dot_api_services_as_overview_scoped_to_season(): void
    {
        // Arrange
        SeasonSession::set(config('services.dotapi.competitive.key'));

        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        Season::factory()->createOne([
            'key' => config('services.dotapi.competitive.key'),
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('player-overview-page', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 1);
    }

    public function test_valid_response_from_all_dot_api_services_as_overview_as_throttled_user(): void
    {
        // Arrange
        SeasonSession::set(SeasonSession::$allSeasonKey);

        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
            'is_throttled' => true,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('player-overview-page', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function test_valid_response_from_all_dot_api_services_as_overview_scoped_to_all(): void
    {
        // Arrange
        SeasonSession::set(SeasonSession::$allSeasonKey);

        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('player-overview-page', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function test_valid_response_from_all_dot_api_services_as_competitive(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::COMPETITIVE,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('competitive-page', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function test_valid_response_from_all_dot_api_services_as_matches(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::MATCHES,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('game-history-table', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function test_valid_response_from_all_dot_api_services_as_custom_matches(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::CUSTOM,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('game-custom-history-table', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function test_valid_response_from_all_dot_api_services_as_lan_matches(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_OK);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        Rank::factory()->createOne([
            'id' => 12,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::LAN,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('game-lan-history-table', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
    }

    public function test_valid_response_from_all_dot_api_services_except_for_career_rank(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);
        $mockCareerRankResponse = (new MockCareerRankService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCareerRankResponse, Response::HTTP_INTERNAL_SERVER_ERROR);

        Playlist::factory()->createOne([
            'uuid' => 1,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        MatchupPlayer::factory()->createOne([
            'player_id' => $player->id,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::LAN,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')
            ->assertDispatchedTo('game-lan-history-table', '$refresh')
            ->assertDispatchedTo('player-card', '$refresh');

        $this->assertDatabaseCount('service_records', 2);
        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'rank_id' => null,
            'next_rank_id' => null,
        ]);
    }
}
