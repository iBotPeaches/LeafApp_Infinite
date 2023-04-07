<?php

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Game;
use App\Models\Scrim;
use App\Services\HaloDotApi\InfiniteInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessScrim implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Scrim $scrim)
    {
        $this->onQueue(QueueName::HCS);
    }

    public function handle(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $this->scrim->games->each(function (Game $game) use ($client) {
            if ($game->outdated) {
                $client->match($game->uuid);
            }
        });

        $this->scrim->is_complete = true;
        $this->scrim->saveOrFail();
    }
}
