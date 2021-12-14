<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class PullXuid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->player->id))->dontRelease()
        ];
    }

    public function handle(): void
    {
        $this->player->syncXuidFromXboxApi();
        if ($this->player->isDirty('xuid')) {
            $this->player->saveOrFail();
        }
    }
}
