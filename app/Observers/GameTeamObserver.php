<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\GameTeam;
use App\Models\Team;

class GameTeamObserver
{
    public function saving(GameTeam $gameTeam): void
    {
        if (empty($gameTeam->team_id)) {
            $gameTeam->team()->associate(
                Team::query()->firstWhere('internal_id', $gameTeam->internal_team_id)
            );
        }
    }
}
