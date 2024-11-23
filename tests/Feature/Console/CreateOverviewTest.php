<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Game;
use App\Models\Map;
use Tests\TestCase;

class CreateOverviewTest extends TestCase
{
    public function test_missing_map_and_game(): void
    {
        $this->artisan('app:create-overview')
            ->expectsQuestion('What is the map uuid?', '123')
            ->expectsOutput('Map/Game not found.')
            ->assertExitCode(1);
    }

    public function test_valid_map(): void
    {
        // Arrange
        $map = Map::factory()->create();

        // Act & Assert
        $this->artisan('app:create-overview')
            ->expectsQuestion('What is the map uuid?', $map->uuid)
            ->assertExitCode(0);
    }

    public function test_valid_game(): void
    {
        // Arrange
        $game = Game::factory()->create();

        // Act & Assert
        $this->artisan('app:create-overview')
            ->expectsQuestion('What is the map uuid?', $game->uuid)
            ->assertExitCode(0);
    }
}
