<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Championship;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Matchup;
use Illuminate\View\View;
use Livewire\Component;

class ChampionshipMatchup extends Component
{
    public Championship $championship;

    public Matchup $matchup;

    public function render(): View
    {
        $this->matchup->load('matchupTeams.players');

        $aggregateStats = $this->matchup->games
            ->map(function (Game $game) {
                return $game->players->map(function (GamePlayer $gamePlayer) {
                    return [
                        'player' => $gamePlayer->player,
                        'kills' => $gamePlayer->kills,
                        'deaths' => $gamePlayer->deaths,
                    ];
                });
            })
            ->flatten(1)
            ->keyBy('player.id');

        return view('livewire.championship-matchup', [
            'championship' => $this->championship,
            'matchup' => $this->matchup,
            'games' => $this->matchup->games,
            'aggregateStats' => $aggregateStats,
        ]);
    }
}
