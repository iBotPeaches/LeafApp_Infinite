<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Player;
use App\Services\HaloDotApi\InfiniteInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class PullCompetitive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 60;

    private Player $player;

    private int $seasonNumber;

    public function __construct(Player $player, ?int $seasonNumber)
    {
        $this->player = $player;
        $this->seasonNumber = $seasonNumber ?? (int) config('services.halodotapi.competitive.season');
        $this->onQueue(QueueName::COMPETITIVE);
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->player->id.$this->seasonNumber.'competitive'))->dontRelease(),
        ];
    }

    public function handle(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $client->competitive($this->player, $this->seasonNumber);
    }
}
