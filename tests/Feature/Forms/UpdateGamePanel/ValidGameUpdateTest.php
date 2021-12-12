<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\UpdateGamePanel;

use App\Http\Livewire\UpdateGamePanel;
use App\Models\Game;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
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

    public function testValidResponseFromAllHaloDotApiServices(): void
    {
        // Arrange
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
    }
}
