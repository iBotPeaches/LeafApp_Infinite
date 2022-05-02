<?php
declare(strict_types = 1);

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Player;
use App\Services\Autocode\Enums\Filter;
use App\Services\Autocode\InfiniteInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class PullServiceRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 720;

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->onQueue(QueueName::RECORDS);
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->player->id . 'records'))->dontRelease()
        ];
    }

    public function handle(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $client->serviceRecord($this->player);
    }
}
