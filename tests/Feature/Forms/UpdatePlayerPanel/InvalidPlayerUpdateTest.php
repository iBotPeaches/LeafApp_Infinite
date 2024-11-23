<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\UpdatePlayerPanel;

use App\Enums\PlayerTab;
use App\Jobs\PullAppearance;
use App\Livewire\UpdatePlayerPanel;
use App\Models\Category;
use App\Models\Level;
use App\Models\Player;
use App\Models\Playlist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\Matches\MockMatchService;
use Tests\Mocks\ServiceRecord\MockServiceRecordService;
use Tests\TestCase;

class InvalidPlayerUpdateTest extends TestCase
{
    use WithFaker;

    public function test_invalid_response_from_all_dot_api_services(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->error401();
        $mockMatchesResponse = (new MockMatchesService)->error404();
        $mockServiceResponse = (new MockServiceRecordService)->error404();

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_UNAUTHORIZED)
            ->push($mockMatchesResponse, Response::HTTP_NOT_FOUND)
            ->push($mockServiceResponse, Response::HTTP_NOT_FOUND);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');
    }

    public function test_invalid_response_due_to_rate_limit(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockAppearanceResponse = (new MockAppearanceService)->invalidSuccess($gamertag);
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->error429();

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_TOO_MANY_REQUESTS);

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

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Rate Limit Hit :( - Try later.');
    }

    public function test_crashing_out_if_unknown_playlist(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockEmptyCustomMatchResponse = (new MockMatchesService)->empty($gamertag);
        $mockMatchResponse = (new MockMatchService)->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyCustomMatchResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');
    }

    public function test_crashing_out_if_new_experience_mode(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockEmptyCustomMatchResponse = (new MockMatchesService)->empty($gamertag);
        $mockMatchResponse = (new MockMatchService)->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);

        Arr::set($mockMatchesResponse, 'data.0.properties.experience', 'unknown-gametype');

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyCustomMatchResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

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

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');

        Bus::assertDispatched(PullAppearance::class);
    }

    public function test_crashing_out_if_new_competitive_mode_in_csr(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->malformed();
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockEmptyCustomMatchResponse = (new MockMatchesService)->empty($gamertag);
        $mockMatchResponse = (new MockMatchService)->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyCustomMatchResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

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

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');

        Bus::assertDispatched(PullAppearance::class);
    }

    public function test_crashing_out_if_new_outcome_mode(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockEmptyCustomMatchResponse = (new MockMatchesService)->empty($gamertag);
        $mockMatchResponse = (new MockMatchService)->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);

        Arr::set($mockMatchesResponse, 'data.0.player.outcome', 'crashed');

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyCustomMatchResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

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

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');

        Bus::assertDispatched(PullAppearance::class);
    }

    public function test_crashing_out_if_new_queue_mode_in_csr(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
        ]);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService)->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService)->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService)->empty($gamertag);
        $mockEmptyCustomMatchResponse = (new MockMatchesService)->empty($gamertag);
        $mockMatchResponse = (new MockMatchService)->success($gamertag, $gamertag);
        $mockServiceResponse = (new MockServiceRecordService)->success($gamertag);

        Arr::set($mockCsrResponse, 'data.0.properties.queue', 'closed');

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyCustomMatchResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

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

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');

        Bus::assertDispatched(PullAppearance::class);
    }
}
