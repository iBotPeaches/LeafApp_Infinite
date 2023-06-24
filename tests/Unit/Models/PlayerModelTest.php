<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Jobs\PullXuid;
use App\Models\Player;
use App\Models\Rank;
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

    public function testNextRankPercentageWithNoRank(): void
    {
        // Arrange
        $player = Player::factory()->makeOne([
            'rank_id' => null,
            'next_rank_id' => null,
            'is_bot' => false,
        ]);

        // Act && Assert
        $this->assertEquals(100.0, $player->percentage_next_rank);
    }

    public function testRankValuesWithNextRank(): void
    {
        // Arrange
        /** @var Rank $rank */
        $rank = Rank::factory()->createOne();
        /** @var Rank $nextRank */
        $nextRank = Rank::factory()->createOne(['id' => 2]);

        $player = Player::factory()->makeOne([
            'xp' => 1610,
            'rank_id' => $rank->id,
            'next_rank_id' => $nextRank->id,
            'is_bot' => false,
        ]);

        // Act && Assert
        $this->assertEquals(100, $player->xp_towards_next_rank);
        $this->assertEquals(100, $player->xp_required_for_next_rank);
        $this->assertEquals(100, $player->percentage_next_rank);
    }
}
