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
        $gametypeKoth = Gamevariant::factory()->createOne([
            'name' => 'King of the Hill',
        ]);
        $gametypeLSS = Gamevariant::factory()->createOne([
            'name' => 'Last Spartan Standing',
        ]);
        $gametypeDodgeball = Gamevariant::factory()->createOne([
            'name' => 'Dodgeball',
        ]);
        $gametypeSlayholds = Gamevariant::factory()->createOne([
            'name' => 'Slayholds',
        ]);

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

        Game::factory()->createOne([
            'map_id' => $map2->id,
            'gamevariant_id' => $gametype2->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $gametypeKoth->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $gametypeLSS->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $gametypeDodgeball->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $gametypeSlayholds->id,
        ]);

        // Act
        $this->artisan('analytics:overviews:refresh')
            ->expectsOutputToContain('Processed '.$game->map->name)
            ->assertOk();
    }

    public function testUnknownGametype(): void
    {
        // Expectations
        $this->expectExceptionMessage('Unable to find base gametype for: not a real gametype');

        // Arrange
        $gametype = Gamevariant::factory()->createOne([
            'name' => 'not a real gametype',
        ]);

        Game::factory()->createOne([
            'gamevariant_id' => $gametype->id,
        ]);

        // Act
        $this->artisan('analytics:overviews:refresh')
            ->assertFailed();
    }
}
