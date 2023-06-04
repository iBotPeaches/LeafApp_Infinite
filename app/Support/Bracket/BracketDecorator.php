<?php

declare(strict_types=1);

namespace App\Support\Bracket;

use App\Models\Matchup;
use App\Models\MatchupTeam;
use Illuminate\Support\Collection;

class BracketDecorator
{
    public array $data = [];

    public function __construct(Collection $matchups)
    {
        $matchups->each(function (Matchup $matchup) {
            $matchup->matchupTeams->each(function (MatchupTeam $matchupTeam) {
                $teamId = $matchupTeam->faceit_id;
                if (! isset($this->data[$teamId])) {
                    $this->data[$teamId] = new BracketResult($matchupTeam);
                }

                $this->data[$teamId]->matchupTeam = $matchupTeam;
                $this->data[$teamId]->parseOutcome();
                $this->data[$teamId]->parsePoints();
            });
        });

        usort($this->data, fn (BracketResult $a, BracketResult $b) => $b->points <=> $a->points);
    }
}
