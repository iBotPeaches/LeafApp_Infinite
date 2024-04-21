<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Game;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RefreshOverviewsTest extends TestCase
{
    use WithFaker;

    public function testValidGenerationOfOverviews(): void
    {
        // Arrange
        $game = Game::factory()->createOne();

        // Act
        $this->artisan('analytics:overviews:refresh')
            ->expectsOutput('Processed '.$game->map->name.' in 0 seconds.');
    }
}
