<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\ScrimTogglePanel;

use App\Http\Livewire\ScrimTogglePanel;
use App\Jobs\PullAppearance;
use App\Jobs\PullXuid;
use App\Models\Category;
use App\Models\Game;
use App\Models\Level;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Matches\MockMatchService;
use Tests\TestCase;

class ValidScrimTogglePanelTest extends TestCase
{
    public function testToggleScrimMode(): void
    {
        // Arrange

        // Act & Assert
        Livewire::test(ScrimTogglePanel::class, [
            'gameIds' => ['a', 'b', 'c'],
        ])
            ->call('syncGameIds', [1, 2, 3])
            ->assertSet('gameIds', [1, 2, 3]);
    }

    public function testCreateScrim(): void
    {
        // Arrange
        Bus::fake([
            PullAppearance::class,
            PullXuid::class,
        ]);

        $mockMatchResponse = (new MockMatchService())->success('a', 'b');

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_OK);

        $user = User::factory()->createOne();
        $game = Game::factory()->createOne();
        Level::factory()->createOne([
            'uuid' => 1,
        ]);

        Category::factory()->createOne([
            'uuid' => 1,
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(ScrimTogglePanel::class, [
            'gameIds' => [$game->id],
        ])
            ->call('createScrim');

        Bus::assertDispatchedTimes(PullAppearance::class, 2);
        Bus::assertDispatchedTimes(PullXuid::class, 2);
    }

    public function testCreateScrimWithNoGames(): void
    {
        // Arrange
        $user = User::factory()->createOne();

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(ScrimTogglePanel::class, [
            'gameIds' => [],
        ])
            ->call('createScrim')
            ->assertEmittedTo('game-custom-history-table', 'toggleScrimMode');
    }
}
