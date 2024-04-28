<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Game;
use App\Models\Map;
use Tests\TestCase;

class CreateOverviewTest extends TestCase
{
    public function testMissingMapAndGame(): void
    {
        $this->artisan('app:create-overview')
            ->expectsQuestion('What is the map uuid?', '123')
            ->expectsOutput('Map/Game not found.')
            ->assertExitCode(1);
    }

    public function testValidMap(): void
    {
        // Arrange
        $map = Map::factory()->create();

        // Act & Assert
        $this->artisan('app:create-overview')
            ->expectsQuestion('What is the map uuid?', $map->uuid)
            ->assertExitCode(0);
    }

    public function testValidGame(): void
    {
        // Arrange
        $game = Game::factory()->create();

        // Act & Assert
        $this->artisan('app:create-overview')
            ->expectsQuestion('What is the map uuid?', $game->uuid)
            ->assertExitCode(0);
    }
}
