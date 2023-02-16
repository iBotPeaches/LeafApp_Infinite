<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Scrim;
use Illuminate\View\View;
use Livewire\Component;

class ScrimPlayers extends Component
{
    public Scrim $scrim;

    public array $mergedStats = [];

    public function render(): View
    {
        $this->scrim->load([
            'games.players.player',
        ]);

        $this->scrim->games->each(function (Game $game) {
            $game->players->each(function (GamePlayer $gamePlayer) {
                $playerId = $gamePlayer->player_id;

                if (! isset($this->mergedStats[$playerId])) {
                    $this->mergedStats[$playerId] = $gamePlayer;
                    $this->mergedStats[$playerId]['gameCount'] = 1;
                } else {
                    $this->mergedStats[$playerId]['gameCount'] += 1;

                    $this->mergedStats[$playerId]['kills'] += $gamePlayer->kills;
                    $this->mergedStats[$playerId]['deaths'] += $gamePlayer->deaths;
                    $this->mergedStats[$playerId]['assists'] += $gamePlayer->assists;
                    $this->mergedStats[$playerId]['kd'] += $gamePlayer->kd;
                    $this->mergedStats[$playerId]['kda'] += $gamePlayer->kda;
                    $this->mergedStats[$playerId]['accuracy'] += $gamePlayer->accuracy;
                    $this->mergedStats[$playerId]['score'] += $gamePlayer->score;
                    $this->mergedStats[$playerId]['rank'] += $gamePlayer->rank;
                    $this->mergedStats[$playerId]['damageDealt'] += $gamePlayer->damage_dealt;
                    $this->mergedStats[$playerId]['damageTaken'] += $gamePlayer->damage_taken;
                }
            });
        });

        foreach ($this->mergedStats as &$mergedStat) {
            $mergedStat['kd'] = $mergedStat['kd'] / $mergedStat['gameCount'];
            $mergedStat['kda'] = $mergedStat['kda'] / $mergedStat['gameCount'];
            $mergedStat['accuracy'] = $mergedStat['accuracy'] / $mergedStat['gameCount'];
            $mergedStat['score'] = $mergedStat['score'] / $mergedStat['gameCount'];
            $mergedStat['rank'] = $mergedStat['rank'] / $mergedStat['gameCount'];
        }

        usort($this->mergedStats, function (GamePlayer $a, GamePlayer $b) {
            return $a['rank'] <=> $b['rank'];
        });

        return view('livewire.scrim-players', [
            'gamePlayers' => $this->mergedStats,
        ]);
    }
}
