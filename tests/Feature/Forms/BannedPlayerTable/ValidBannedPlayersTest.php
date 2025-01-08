<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\BannedPlayerTable;

use App\Livewire\BannedPlayerTable;
use App\Models\Player;
use App\Models\PlayerBan;
use Livewire\Livewire;
use Tests\TestCase;

class ValidBannedPlayersTest extends TestCase
{
    public function test_valid_loading_of_banned_players(): void
    {
        // Arrange
        $player = Player::factory()
            ->hasAttached(PlayerBan::factory())
            ->createOne([
                'is_cheater' => true,
            ]);

        // Act & Assert
        Livewire::test(BannedPlayerTable::class)
            ->assertViewHas('players')
            ->assertSee($player->gamertag);
    }
}
