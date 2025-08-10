<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Category;
use App\Models\Game;
use App\Models\Gamevariant;
use App\Models\Level;
use App\Models\Map;
use App\Models\Overview;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RefreshOverviewsTest extends TestCase
{
    use WithFaker;

    public function test_valid_generation_of_overviews(): void
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
        $hotswap = Gamevariant::factory()->createOne([
            'name' => 'Hotswap',
        ]);
        $gametypeFirefight = Gamevariant::factory()->createOne([
            'name' => 'Firefight | Hard',
        ]);
        $miniGame = Gamevariant::factory()->createOne([
            'name' => 'Survive The Undead',
        ]);
        $ninjaBall = Gamevariant::factory()->createOne([
            'name' => 'Ninja Ball',
        ]);
        $ninjaNaut = Gamevariant::factory()->createOne([
            'name' => 'Ninjanaut',
        ]);
        $alphaZombies = Gamevariant::factory()->createOne([
            'name' => 'Alpha Zombies',
        ]);
        $arena = Gamevariant::factory()
            ->for(Category::factory()->set('name', 'Slayer'))
            ->createOne([
                'name' => 'Arena',
            ]);
        $castleWars = Gamevariant::factory()
            ->createOne([
                'name' => 'Castle Wars',
            ]);
        $neutralBomb = Gamevariant::factory()
            ->createOne([
                'name' => 'Neutral Bomb',
            ]);
        $classic = Gamevariant::factory()
            ->for(Category::factory()->set('name', 'Firefight'))
            ->createOne([
                'name' => 'Classic',
            ]);
        $octane = Gamevariant::factory()
            ->createOne([
                'name' => 'Octane',
            ]);
        $firefightThirdPerson = Gamevariant::factory()
            ->createOne([
                'name' => '3P | Classic',
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

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $gametypeFirefight->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $miniGame->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $ninjaBall->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $ninjaNaut->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $alphaZombies->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $arena->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $castleWars->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $neutralBomb->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $classic->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $hotswap->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $octane->id,
        ]);

        Game::factory()->createOne([
            'map_id' => $map1->id,
            'gamevariant_id' => $firefightThirdPerson->id,
        ]);

        // Act
        $this->artisan('analytics:overviews:refresh')
            ->expectsOutputToContain('Processed '.$game->map->name)
            ->assertOk();
    }

    public function test_unknown_gametype(): void
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

    public function test_overview_recently_generated_skipped(): void
    {
        $game = Game::factory()->createOne();
        Overview::factory()->createOne([
            'name' => $game->map->name,
            'slug' => Str::slug($game->map->name),
            'updated_at' => now()->subMinute(),
        ]);

        // Act
        $this->artisan('analytics:overviews:refresh')
            ->assertOk();

        $this->assertDatabaseCount('overviews', 1);
    }
}
