<?php

declare(strict_types=1);

namespace Tests\Feature\Forms;

use App\Http\Livewire\PlayerBadges;
use App\Models\Player;
use Livewire\Livewire;
use Tests\TestCase;

class EmptyPlayerBadgeTest extends TestCase
{
    public function testLoadPlayerBadgesWithNothing(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(PlayerBadges::class, [
            'player' => $player,
        ])
            ->call('loadBadges')
            ->assertSuccessful();
    }
}
