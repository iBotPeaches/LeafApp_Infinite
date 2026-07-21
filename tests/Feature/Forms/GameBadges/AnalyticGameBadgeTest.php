<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\GameBadges;

use App\Livewire\GameBadges;
use App\Models\Analytic;
use App\Models\Game;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticGameBadgeTest extends TestCase
{
    public function testLoadingAnalyticsOnBadges(): void
    {
        // Arrange
        $game = Game::factory()->createOne();

        Analytic::factory()->createOne([
            'game_id' => $game->id,
        ]);

        // Act & Assert
        Livewire::test(GameBadges::class, [
            'game' => $game,
        ])
            ->call('loadBadges')
            ->assertSuccessful();
    }
}
