<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\UpdateGamePanel;

use App\Jobs\PullAppearance;
use App\Jobs\PullXuid;
use App\Livewire\UpdateGamePanel;
use App\Models\Category;
use App\Models\Game;
use App\Models\Level;
use App\Services\DotApi\Enums\PlayerType;
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
            'runUpdate' => false,
        ])
            ->assertViewHas('color', 'is-info')
            ->assertViewHas('message', 'Checking for missing players.');

        Http::assertNothingSent();
    }

    public function testValidResponseFromAllDotApiServicesForUnresolvedGamer(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = 'test';
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.players.1.attributes.resolved', false);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        $this->assertDatabaseMissing('players', [
            'gamertag' => $gamertag2,
        ]);

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'was_pulled' => false,
        ]);

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServicesForABot(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.players.1.details.type', PlayerType::BOT);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServicesForUnranked(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.details.playlist.properties.queue', null);
        Arr::set($mockMatchResponse, 'data.details.playlist.properties.input', null);
        Arr::set($mockMatchResponse, 'data.players.0.progression.csr.pre_match.value', -1);
        Arr::set($mockMatchResponse, 'data.players.0.progression.csr.post_match.value', -1);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServices(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServicesAsSlayer(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.details.ugcgamevariant.name', 'Slayer');

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServicesAsCtf(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.details.ugcgamevariant.name', 'CTF');

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServicesAsOddball(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.details.ugcgamevariant.name', 'Oddball');

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServicesAsStrongholds(): void
    {
        // Arrange
        Queue::fake();
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);

        Arr::set($mockMatchResponse, 'data.details.ugcgamevariant.name', 'Strongholds');

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Queue::assertPushed(PullAppearance::class);
    }

    public function testValidResponseFromAllDotApiServicesIncludingAppearance(): void
    {
        // Arrange
        Bus::fake(PullXuid::class);
        $gamertag = $this->faker->word.$this->faker->numerify;
        $gamertag2 = $this->faker->word.$this->faker->numerify;

        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag2);
        $mockAppearanceResponse = (new MockAppearanceService())->invalidSuccess($gamertag);
        $mockAppearance2Response = (new MockAppearanceService())->invalidSuccess($gamertag2);

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockAppearance2Response, Response::HTTP_OK);

        $game = Game::factory()->createOne([
            'uuid' => Arr::get($mockMatchResponse, 'data.id'),
        ]);

        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
            'runUpdate' => true,
        ])
            ->assertViewHas('color', 'is-success')
            ->assertViewHas('message', 'Game updated!')
            ->assertDispatchedTo('game-page', '$refresh');

        Bus::assertDispatchedTimes(PullXuid::class, 2);
    }
}
