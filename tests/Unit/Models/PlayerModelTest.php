<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Jobs\PullXuid;
use App\Models\Player;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class PlayerModelTest extends TestCase
{
    public function testBotDoesNotDispatchObserverXuid(): void
    {
        // Arrange
        Bus::fake([
            PullXuid::class,
        ]);
        $player = Player::factory()->makeOne([
            'xuid' => null,
            'is_bot' => true,
        ]);

        // Act
        $player->saveOrFail();

        // Assert
        Bus::assertNotDispatched(PullXuid::class);
    }

    public function testPlayerDispatchesObserverXuid(): void
    {
        // Arrange
        Bus::fake([
            PullXuid::class,
        ]);
        $player = Player::factory()->makeOne([
            'xuid' => null,
            'is_bot' => false,
        ]);

        // Act
        $player->saveOrFail();

        // Assert
        Bus::assertDispatched(PullXuid::class);
    }
}