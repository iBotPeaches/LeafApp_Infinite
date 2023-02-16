<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\GameLanHistoryTable;

use App\Http\Livewire\GameLanHistoryTable;
use App\Models\Player;
use Livewire\Livewire;
use Tests\TestCase;

class MissingLanGameHistoryTableTest extends TestCase
{
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(GameLanHistoryTable::class, [
            'player' => $player,
        ])
            ->call('render')
            ->assertViewHas('games');
    }
}
