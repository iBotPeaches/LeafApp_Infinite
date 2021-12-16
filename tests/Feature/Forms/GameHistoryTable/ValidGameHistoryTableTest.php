<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\GameHistoryTable;

use App\Enums\Outcome;
use App\Http\Livewire\GameHistoryTable;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;
use Tests\TestCase;

class ValidGameHistoryTableTest extends TestCase
{
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->count(8)
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
        Livewire::test(GameHistoryTable::class, [
            'player' => $player
        ])
            ->assertViewHas('games')
            ->assertSee($game->playlist->title)
            ->assertSee($game->map->name)
            ->assertSee($game->category->name)
            ->assertSee($game->personal->outcome->description)
            ->assertSee($game->personal->kills)
            ->assertSee($game->personal->deaths)
            ->assertSee($game->personal->kd)
            ->assertSee($game->personal->kda)
            ->assertSee($game->personal->accuracy)
            ->assertSee($game->personal->score)
            ->assertSee($game->personal->rank)
            ->assertSee($game->occurred_at->diffForHumans());
    }
}
