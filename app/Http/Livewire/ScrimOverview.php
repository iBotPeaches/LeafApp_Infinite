<?php
declare(strict_types = 1);

namespace App\Http\Livewire;

use App\Enums\Outcome;
use App\Models\Game;
use App\Models\Scrim;
use Illuminate\View\View;
use Livewire\Component;

class ScrimOverview extends Component
{
    public Scrim $scrim;

    public function render(): View
    {
        /** @var Game $game */
        $this->scrim->load('games.teams.players.player');
        $game = $this->scrim->games->first();

        $team1 = $game->findTeamFromInternalId(0);
        $team1['points'] = 0;
        $team2 = $game->findTeamFromInternalId(1);
        $team2['points'] = 0;

        $this->scrim->games->each(function (Game $game) use ($team1, $team2) {
            if ($game->findTeamFromInternalId($team1->internal_team_id)->outcome->is(Outcome::WIN())) {
                $team1['points'] += 1;
            }
            if ($game->findTeamFromInternalId($team2->internal_team_id)->outcome->is(Outcome::WIN())) {
                $team2['points'] += 1;
            }
        });

        return view('livewire.scrim-overview', [
            'team1' => $team1,
            'team2' => $team2,
        ]);
    }
}
