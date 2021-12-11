<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\UpdatePlayerPanel;

use App\Enums\PlayerTab;
use App\Http\Livewire\UpdatePlayerPanel;
use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\ServiceRecord\MockServiceRecordService;
use Tests\TestCase;

class InvalidPlayerUpdateTest extends TestCase
{
    use WithFaker;

    public function testInvalidResponseFromAllHaloDotApiServices(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->error401();
        $mockMatchesResponse = (new MockMatchesService())->error404();
        $mockServiceResponse = (new MockServiceRecordService())->error404();

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_UNAUTHORIZED)
            ->push($mockMatchesResponse, Response::HTTP_NOT_FOUND)
            ->push($mockServiceResponse, Response::HTTP_NOT_FOUND);

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

    public function testInvalidResponseDueToRateLimit(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->error429();

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_TOO_MANY_REQUESTS);

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
            ->assertViewHas('message', 'Rate Limit Hit :( - Try later.');
    }

    public function testCrashingOutIfNewExperienceMode(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        // Set values into responses that "fake" a private account.
        Arr::set($mockMatchesResponse, 'data.0.experience', 'unknown-gametype');

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

    public function testCrashingOutIfNewOutcomeMode(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        // Set values into responses that "fake" a private account.
        Arr::set($mockMatchesResponse, 'data.0.player.outcome', 'crashed');

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

    public function testCrashingOutIfNewQueueMode(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        // Set values into responses that "fake" a private account.
        Arr::set($mockCsrResponse, 'data.0.queue', 'closed');

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
}
