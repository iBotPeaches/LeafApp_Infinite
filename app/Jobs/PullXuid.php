<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class PullXuid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Player $player;

    public int $tries = 1;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->onQueue(QueueName::XUID);
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->player->id . 'xuid'))->dontRelease(),
            (new ThrottlesExceptions(5, 10))
        ];
    }

    private function checkForGamertagChange(): void
    {
        $player = Player::query()
            ->where('xuid', $this->player->xuid)
            ->where('id', '<>', $this->player->id)
            ->first();

        if ($player) {
            $player->deleteOrFail();
        }
    }

    public function handle(): void
    {
        if (!config('services.xboxapi.enabled')) {
            return;
        }

        $this->player->syncXuidFromXboxApi();
        if ($this->player->isDirty('xuid')) {
            if ($this->player->xuid) {
                $this->checkForGamertagChange();
            }
            $this->player->saveOrFail();
        }
    }
}
