<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\Player;
use App\Services\Autocode\InfiniteInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class PullMatchHistory implements ShouldQueue
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
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $client->matches($this->player);
    }
}
