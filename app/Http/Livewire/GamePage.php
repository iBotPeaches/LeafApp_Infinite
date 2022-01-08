<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Game;
use Illuminate\View\View;
use Livewire\Component;

class GamePage extends Component
{
    public Game $game;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh'
    ];

    public function render(): View
    {
        $groupedPlayers = $this->game->players->groupBy('game_team_id');

        return view('livewire.game-page', [
            'game' => $this->game,
            'groupedGamePlayers' => $groupedPlayers
        ]);
    }
}
