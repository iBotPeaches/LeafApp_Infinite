<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Enums\MedalType;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Medal;
use Illuminate\View\View;
use Livewire\Component;

class GamePage extends Component
{
    public Game $game;
    public array $powerfulMedals = [];

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh'
    ];

    public function render(): View
    {
        $groupedPlayers = $this->game->players->groupBy('game_team_id');

        $this->game->players
            ->each(function (GamePlayer $gamePlayer) {
                $gamePlayer->hydrated_medals->filter(function (Medal $medal) {
                    return $medal->type->in([
                        MedalType::LEGENDARY(),
                        MedalType::HEROIC()
                    ]);
                })->each(function (Medal $medal) use ($gamePlayer) {
                    $this->powerfulMedals[$medal->id]['medal'] = $medal;

                    // Pass on our medal specific count and attach to the specific gamePlayer
                    $gamePlayer['medal_' . $medal->id] = $medal['count'];

                    $this->powerfulMedals[$medal->id]['players'][$gamePlayer->id] = $gamePlayer;
                });
            });

        return view('livewire.game-page', [
            'game' => $this->game,
            'groupedGamePlayers' => $groupedPlayers,
            'powerfulMedals' => $this->powerfulMedals
        ]);
    }
}
