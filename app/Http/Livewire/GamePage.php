<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Medal;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Livewire\Component;

class GamePage extends Component
{
    public Game $game;
    public array $medals = [];

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh'
    ];

    public function render(): View
    {
        $this->game->load('teams.players');
        $groupedPlayers = $this->game->players->groupBy('game_team_id', true);

        if (! $this->game->outdated) {
            $this->game->players->each(function (GamePlayer $gamePlayer) {
                $gamePlayer->hydrated_medals->each(function (Medal $medal) use ($gamePlayer) {
                    $this->medals[$medal->id]['medal'] = $medal;

                    // Pass on our medal specific count and attach to the specific gamePlayer
                    $gamePlayer['medal_' . $medal->id] = $medal['count'];

                    $this->medals[$medal->id]['players'][$gamePlayer->id] = $gamePlayer;
                });
            });
        }

        foreach ($this->medals as &$medal) {
            usort($medal['players'], function (GamePlayer $a, GamePlayer $b) use ($medal) {
                return Arr::get($b, 'medal_' . $medal['medal']->id) <=> Arr::get($a, 'medal_' . $medal['medal']->id);
            });
        }

        usort($this->medals, function (array $a, array $b) {
            return Arr::get($a, 'medal.difficulty.value') <=> Arr::get($b, 'medal.difficulty.value');
        });

        return view('livewire.game-page', [
            'game' => $this->game,
            'groupedGamePlayers' => $groupedPlayers,
            'powerfulMedals' => $this->medals
        ]);
    }
}
