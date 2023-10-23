<?php

declare(strict_types=1);

namespace Tests\Feature\Forms;

use App\Livewire\GameBadges;
use App\Models\Game;
use Livewire\Livewire;
use Tests\TestCase;

class EmptyGameBadgeTest extends TestCase
{
    public function testLoadGameBadgesWithNothing(): void
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
