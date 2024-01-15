<?php

declare(strict_types=1);

namespace App\Support\Bracket;

use App\Enums\Bracket;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use Illuminate\Support\Collection;

class BracketDecorator
{
    public array $data = [];

    public function __construct(Bracket $bracket, Collection $matchups)
    {
        $matchups->each(function (Matchup $matchup) use ($bracket) {
            $matchup->matchupTeams->each(function (MatchupTeam $matchupTeam) use ($bracket) {
                $teamId = $matchupTeam->faceit_id;
                if (! isset($this->data[$teamId])) {
                    $this->data[$teamId] = new BracketResult($bracket, $matchupTeam);
                }

                $this->data[$teamId]->matchupTeam = $matchupTeam;
                $this->data[$teamId]->parseOutcome();
                $this->data[$teamId]->parsePoints();
            });
        });

        usort($this->data, fn (BracketResult $a, BracketResult $b) => $b->points <=> $a->points);
    }
}
