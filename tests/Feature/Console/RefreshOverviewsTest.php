<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Game;
use App\Models\Gamevariant;
use App\Models\Level;
use App\Models\Map;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RefreshOverviewsTest extends TestCase
{
    use WithFaker;

    public function testValidGenerationOfOverviews(): void
    {
        // Arrange
        $level = Level::factory()->createOne();
        $gametype1 = Gamevariant::factory()->createOne();
        $gametype2 = Gamevariant::factory()->createOne();

        $map1 = Map::factory()->createOne([
            'name' => 'Absolute',
            'level_id' => $level->id,
        ]);
        $map2 = Map::factory()->createOne([
            'name' => 'Absolute',
            'level_id' => $level->id,
        ]);

        $game = Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $gametype1->id,
        ]);

        $game2 = Game::factory()->createOne([
            'map_id' => $map2->id,
            'gamevariant_id' => $gametype2->id,
        ]);

        // Act
        $this->artisan('analytics:overviews:refresh')
            ->expectsOutputToContain('Processed '.$game->map->name);
    }
}
