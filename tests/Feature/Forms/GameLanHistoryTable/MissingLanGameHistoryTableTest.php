<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\GameLanHistoryTable;

use App\Livewire\GameLanHistoryTable;
use App\Models\Player;
use Livewire\Livewire;
use Tests\TestCase;

class MissingLanGameHistoryTableTest extends TestCase
{
    public function test_valid_response_from_dot_api(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(GameLanHistoryTable::class, [
            'player' => $player,
        ])
            ->assertViewHas('games');
    }
}
