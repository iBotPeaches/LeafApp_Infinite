<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\UpdateGamePanel;

use App\Http\Livewire\UpdateGamePanel;
use App\Jobs\PullAppearance;
use App\Jobs\PullXuid;
use App\Models\Game;
use App\Services\Autocode\Enums\PlayerType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\Matches\MockMatchService;
use Tests\TestCase;

class ValidGameUpdateTest extends TestCase
{
    use WithFaker;

    public function testInitialPageLoadDeferredFromApiCalls(): void
    {
        // Arrange
        Http::fake();
        $game = Game::factory()->createOne();

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => false
        ])
            ->call('render')
            ->assertViewHas('color', 'is-info')
            ->assertViewHas('message', 'Checking for missing players.');

        Http::assertNothingSent();
    }

    public function testValidResponseFromAllHaloDotApiServicesForABot(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word . $this->faker->numerify;
        $gamertag2 = $this->faker->word . $this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.players.1.type', PlayerType::BOT);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id')
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')

            // TODO - I cannot assert to a specific component - https://github.com/livewire/livewire/discussions/4298
            ->assertEmitted('$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllHaloDotApiServices(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word . $this->faker->numerify;
        $gamertag2 = $this->faker->word . $this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id')
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')

            // TODO - I cannot assert to a specific component - https://github.com/livewire/livewire/discussions/4298
            ->assertEmitted('$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllHaloDotApiServicesIncludingAppearance(): void
    {
        // Arrange
        Bus::fake(PullXuid::class);
        $gamertag = $this->faker->word . $this->faker->numerify;
        $gamertag2 = $this->faker->word . $this->faker->numerify;

        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);
        $mockAppearanceResponse = (new MockAppearanceService())->success($gamertag);
        $mockAppearance2Response = (new MockAppearanceService())->success($gamertag2);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockAppearance2Response, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id')
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')

            // TODO - I cannot assert to a specific component - https://github.com/livewire/livewire/discussions/4298
            ->assertEmitted('$refresh');

        Bus::assertDispatchedTimes(PullXuid::class, 2);
    }
}
