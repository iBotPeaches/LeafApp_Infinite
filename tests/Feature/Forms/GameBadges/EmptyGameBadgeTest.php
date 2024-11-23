<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\GameBadges;

use App\Livewire\GameBadges;
use App\Models\Game;
use Livewire\Livewire;
use Tests\TestCase;

class EmptyGameBadgeTest extends TestCase
{
    public function test_load_game_badges_with_nothing(): void
    {
        // Arrange
        $game = Game::factory()->createOne();

        // Act & Assert
        Livewire::test(GameBadges::class, [
            'game' => $game,
        ])
            ->call('loadBadges')
            ->assertSuccessful();
    }
}
