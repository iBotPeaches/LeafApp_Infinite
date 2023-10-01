<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\PlayerBadges;

use App\Http\Livewire\PlayerBadges;
use App\Models\Analytic;
use App\Models\Player;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticPlayerBadgeTest extends TestCase
{
    public function testLoadingAnalyticsOnBadges(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        Analytic::factory()->createOne([
            'player_id' => $player->id,
        ]);

        // Act & Assert
        Livewire::test(PlayerBadges::class, [
            'player' => $player,
        ])
            ->call('loadBadges')
            ->assertSuccessful();
    }
}
