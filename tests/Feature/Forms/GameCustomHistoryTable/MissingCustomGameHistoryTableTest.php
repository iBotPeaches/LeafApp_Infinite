<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\GameCustomHistoryTable;

use App\Livewire\GameCustomHistoryTable;
use App\Models\Player;
use Livewire\Livewire;
use Tests\TestCase;

class MissingCustomGameHistoryTableTest extends TestCase
{
    public function test_valid_response_from_dot_api(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(GameCustomHistoryTable::class, [
            'player' => $player,
        ])
            ->assertViewHas('games');
    }
}
