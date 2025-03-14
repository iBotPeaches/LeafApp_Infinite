<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\GameHistoryTable;

use App\Livewire\GameHistoryTable;
use App\Models\Player;
use Livewire\Livewire;
use Tests\TestCase;

class MissingGameHistoryTableTest extends TestCase
{
    public function test_valid_response_from_dot_api(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(GameHistoryTable::class, [
            'player' => $player,
        ])
            ->assertViewHas('games');
    }
}
