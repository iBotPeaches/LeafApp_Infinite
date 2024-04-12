<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class CheckForUnban extends Command
{
    protected $signature = 'app:check-for-unban';

    protected $description = 'Command description';

    public function handle(): int
    {
        $cursor = Player::query()
            ->where('is_cheater', true)
            ->whereHas('bans', function ($query) {
                $query->where('ends_at', '<', now());
            })
            ->cursor();

        foreach ($cursor as $player) {
            $player->is_cheater = false;
            $player->save();

            $this->info("Player {$player->gamertag} has been unbanned.");
        }

        return self::SUCCESS;
    }
}
