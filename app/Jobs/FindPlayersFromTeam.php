<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\MatchupTeam;
use App\Models\Player;
use App\Services\DotApi\InfiniteInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class FindPlayersFromTeam implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MatchupTeam $team;

    public function __construct(MatchupTeam $team)
    {
        $this->team = $team;
        $this->onQueue(QueueName::HCS);
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping($this->team->faceit_id)->dontRelease(),
        ];
    }

    public function handle(): void
    {
        $client = App::make(InfiniteInterface::class);

        foreach ($this->team->faceitPlayers->whereNull('player_id') as $teamPlayer) {
            $player = Player::query()->firstWhere('gamertag', $teamPlayer->faceit_name);
            if (empty($player) && ! config('services.dotapi.disabled')) {
                $player = $client->appearance($teamPlayer->faceit_name);
            }

            if ($player) {
                $teamPlayer->player()->associate($player);
                $teamPlayer->save();
            }
        }
    }
}
