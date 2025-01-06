<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Player;
use App\Models\PlayerBan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\SerializesModels;

class PullXuid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Player $player;

    public int $tries = 1;

    public int $retryAfter = 0;

    public bool $failOnTimeout = true;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->onQueue(QueueName::XUID);
    }

    public function middleware(): array
    {
        return [
            (new ThrottlesExceptions(5, 10)),
        ];
    }

    private function checkForGamertagChange(): void
    {
        $player = Player::query()
            ->where('xuid', $this->player->xuid)
            ->where('id', '<>', $this->player->id)
            ->first();

        if ($player) {
            $this->player->is_botfarmer = $player->is_botfarmer;
            $this->player->is_forced_farmer = $player->is_forced_farmer;
            $this->player->is_cheater = $player->is_cheater;
            $this->player->is_donator = $player->is_donator;

            PlayerBan::query()
                ->where('player_id', $player->id)
                ->update(['player_id' => $this->player->id]);

            $this->player->save();

            $player->deleteOrFail();
        }
    }

    public function handle(): void
    {
        if (config('services.dotapi.xuid_disabled') || $this->player->xuid) {
            $this->delete();

            return;
        }

        $this->player->syncXuidFromXboxApi();
        if ($this->player->isDirty('xuid')) {
            if ($this->player->xuid) {
                $this->checkForGamertagChange();
            }
            $this->player->save();
        }
    }
}
