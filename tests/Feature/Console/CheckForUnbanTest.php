<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Player;
use App\Models\PlayerBan;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class CheckForUnbanTest extends TestCase
{
    use WithFaker;

    public function test_unbanning(): void
    {
        // Arrange
        $playerBan = PlayerBan::factory()
            ->for(Player::factory()->state(['is_cheater' => true]))
            ->createOne([
                'ends_at' => now()->subDay(),
            ]);

        // Act & Assert
        $this
            ->artisan('app:check-for-unban')
            ->assertExitCode(CommandAlias::SUCCESS)
            ->expectsOutput("Player {$playerBan->player->gamertag} has been unbanned.");
    }

    public function test_unban_fails_on_active_cheater(): void
    {
        // Arrange
        $playerBan = PlayerBan::factory()
            ->for(Player::factory()->state(['is_cheater' => true]))
            ->createOne([
                'ends_at' => now()->addDay(),
            ]);

        // Act & Assert
        $this
            ->artisan('app:check-for-unban')
            ->assertExitCode(CommandAlias::SUCCESS)
            ->doesntExpectOutput("Player {$playerBan->player->gamertag} has been unbanned.");
    }
}
