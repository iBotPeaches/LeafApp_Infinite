<?php
declare(strict_types=1);

namespace App\Observers;

use App\Jobs\PullXuid;
use App\Models\Player;

class PlayerObserver
{
    public function saved(Player $player): void
    {
        if (empty($player->xuid)) {
            PullXuid::dispatch($player);
        }
    }
}
