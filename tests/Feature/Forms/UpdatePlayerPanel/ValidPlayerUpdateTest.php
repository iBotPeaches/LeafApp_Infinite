<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\UpdatePlayerPanel;

use App\Enums\PlayerTab;
use App\Http\Livewire\CompetitivePage;
use App\Http\Livewire\GameHistoryTable;
use App\Http\Livewire\OverviewPage;
use App\Http\Livewire\UpdatePlayerPanel;
use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\ServiceRecord\MockServiceRecordService;
use Tests\TestCase;

class ValidPlayerUpdateTest extends TestCase
{
    use WithFaker;

    public function testInvalidResponseFromAllHaloDotApiServices(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->error401();
        $mockMatchesResponse = (new MockMatchesService())->error404();
        $mockServiceResponse = (new MockServiceRecordService())->error429();

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
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
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');
    }

    public function testAutomaticallyMarkedPrivateIfInvalidRecord(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        // Set values into responses that "fake" a private account.
        Arr::set($mockServiceResponse, 'data.time_played.seconds', 0);
        Arr::set($mockServiceResponse, 'data.total_score', 0);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
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

        $cacheKey = 'player-profile-' . $player->id . md5($player->gamertag);
        Cache::put($cacheKey, true);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => PlayerTab::OVERVIEW,
            'runUpdate' => false
        ])
            ->call('render')
            ->assertViewHas('color', 'is-dark')
            ->assertViewHas('message', 'Profile was recently updated. Check back soon.');

        Http::assertNothingSent();
    }

    /** @dataProvider validPageDataProvider */
    public function testValidResponseFromAllHaloDotApiServices(string $type, string $event): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        // Act & Assert
        Livewire::test(UpdatePlayerPanel::class, [
            'player' => $player,
            'type' => $type,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Profile updated!')

            // TODO - I cannot assert to a specific component - https://github.com/livewire/livewire/discussions/4298
            ->assertEmitted('$refresh');
    }

    public function validPageDataProvider(): array
    {
        return [
            'overview' => [
                'type' => PlayerTab::OVERVIEW,
                'event' => OverviewPage::class,
            ],
            'competitive' => [
                'type' => PlayerTab::COMPETITIVE,
                'event' => CompetitivePage::class,
            ],
            'matches' => [
                'type' => PlayerTab::MATCHES,
                'event' => GameHistoryTable::class,
            ]
        ];
    }
}
