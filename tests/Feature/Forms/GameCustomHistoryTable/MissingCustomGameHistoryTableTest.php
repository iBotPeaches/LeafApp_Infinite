<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\GameCustomHistoryTable;

use App\Http\Livewire\GameCustomHistoryTable;
use App\Models\Player;
use Livewire\Livewire;
use Tests\TestCase;

class MissingCustomGameHistoryTableTest extends TestCase
{
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(GameCustomHistoryTable::class, [
            'player' => $player
        ])
            ->call('render')
            ->assertViewHas('games');
    }
}
