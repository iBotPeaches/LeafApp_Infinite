<?php

declare(strict_types=1);

namespace App\Support\Bracket;

use App\Enums\Outcome;
use App\Models\MatchupTeam;

class BracketResult
{
    public int $losses = 0;

    public int $wins = 0;

    public int $points = 0;

    public function __construct(public MatchupTeam $matchupTeam)
    {
        //
    }

    public function parseOutcome(): self
    {
        switch ($this->matchupTeam->outcome) {
            case Outcome::LOSS():
                $this->losses++;
                break;
            case Outcome::WIN():
                $this->wins++;
                break;
        }

        return $this;
    }

    public function parsePoints(): self
    {
        $this->points += (int) $this->matchupTeam->points;

        return $this;
    }
}
