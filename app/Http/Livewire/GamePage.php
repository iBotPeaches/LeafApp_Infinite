<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Game;
use Illuminate\View\View;
use Livewire\Component;

class GamePage extends Component
{
    public Game $game;

    public function render(): View
    {
        $groupedPlayers = $this->game->players
            ->sortByDesc('score')
            ->groupBy('game_team_id');

        return view('livewire.game-page', [
            'game' => $this->game,
            'groupedGamePlayers' => $groupedPlayers
        ]);
    }
}
