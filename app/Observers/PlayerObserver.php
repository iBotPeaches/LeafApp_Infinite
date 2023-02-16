<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\PullXuid;
use App\Models\Player;
use Illuminate\Support\Facades\Cache;

class PlayerObserver
{
    public function saved(Player $player): void
    {
        $cacheKey = 'xuid-pull-'.$player->id;

        if (empty($player->xuid) && Cache::missing($cacheKey)) {
            PullXuid::dispatch($player);
            Cache::put($cacheKey, time(), now()->addDays(30));
        }
    }
}
