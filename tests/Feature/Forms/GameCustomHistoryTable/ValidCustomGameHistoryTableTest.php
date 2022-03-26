<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\GameCustomHistoryTable;

use App\Enums\Outcome;
use App\Http\Livewire\GameCustomHistoryTable;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;
use Tests\TestCase;

class ValidCustomGameHistoryTableTest extends TestCase
{
    public function testToggleScrimMode(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(GameCustomHistoryTable::class, [
            'player' => $player
        ])
            ->call('toggleScrimMode')
            ->assertSet('isScrimEditor', true);
    }

    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->for(Game::factory()->state([
                'playlist_id' => null
            ]))
            ->count(3)
            ->state(new Sequence(
                ['outcome' => Outcome::WIN],
                ['outcome' => Outcome::LOSS],
                ['outcome' => Outcome::LEFT],
                ['outcome' => Outcome::DRAW],
            ))
            ->create([
                'player_id' => $player->id
            ]);

        /** @var Game $game */
        $game = $player->games->first();

        // Act & Assert
        Livewire::test(GameCustomHistoryTable::class, [
            'player' => $player
        ])
            ->assertViewHas('games')
            ->assertSee($game->map->name)
            ->assertSee($game->category->name)
            ->assertSee($game->personal->outcome->description)
            ->assertSee($game->personal->kills)
            ->assertSee($game->personal->deaths)
            ->assertSee($game->personal->assists)
            ->assertSee($game->personal->kd)
            ->assertSee($game->personal->kda)
            ->assertSee($game->personal->accuracy)
            ->assertSee($game->personal->rank)
            ->assertSee($game->occurred_at->diffForHumans());
    }
}
