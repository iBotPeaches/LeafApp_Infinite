<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\GameHistoryTable;

use App\Http\Livewire\GameHistoryTable;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\TestCase;

class ValidGameHistoryTableTest extends TestCase
{
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        $mockResponse = (new MockMatchesService())->success();
        $gamertag = Arr::get($mockResponse, 'additional.gamertag');
        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);
        GamePlayer::factory()->createOne([
            'player_id' => $player->id
        ]);

        /** @var Game $game */
        $game = $player->games->first();

        // Act & Assert
        Livewire::test(GameHistoryTable::class, [
            'player' => $player
        ])
            ->call('render')
            ->assertViewHas('games')
            ->assertSee($game->experience->description)
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
