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
    public function test_bot_does_not_dispatch_observer_xuid(): void
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
        $player->save();

        // Assert
        Bus::assertNotDispatched(PullXuid::class);
    }

    public function test_player_dispatches_observer_xuid(): void
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
        $player->save();

        // Assert
        Bus::assertDispatched(PullXuid::class);
    }

    public function test_next_rank_percentage_with_no_rank(): void
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

    public function test_next_rank_percentage_is_between60and80(): void
    {
        // Arrange
        $player = Player::factory()->makeOne([
            'xp' => 650,
            'rank_id' => Rank::factory(['id' => 2, 'threshold' => 40]),
            'next_rank_id' => Rank::factory(['id' => 3, 'threshold' => 1000, 'required' => 1000]),
            'is_bot' => false,
        ]);

        // Act && Assert
        $this->assertEquals(61.0, $player->percentage_next_rank);
        $this->assertEquals('is-primary', $player->percentage_next_rank_color);
    }

    public function test_next_rank_percentage_is_between40and60(): void
    {
        // Arrange
        $player = Player::factory()->makeOne([
            'xp' => 450,
            'rank_id' => Rank::factory(['id' => 2, 'threshold' => 40]),
            'next_rank_id' => Rank::factory(['id' => 3, 'threshold' => 1000, 'required' => 1000]),
            'is_bot' => false,
        ]);

        // Act && Assert
        $this->assertEquals(41.0, $player->percentage_next_rank);
        $this->assertEquals('is-warning', $player->percentage_next_rank_color);
    }

    public function test_rank_values_with_next_rank(): void
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
