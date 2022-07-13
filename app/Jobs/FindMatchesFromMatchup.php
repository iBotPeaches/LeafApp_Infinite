<?php

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Game;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Pivots\MatchupGame;
use App\Models\Player;
use App\Services\Autocode\Enums\Mode;
use App\Services\Autocode\InfiniteInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FindMatchesFromMatchup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Matchup $matchup;
    private ?InfiniteInterface $client;
    private array $processedGameIds = [];

    public function __construct(Matchup $matchup)
    {
        $this->matchup = $matchup;
        $this->onQueue(QueueName::HCS);
    }

    public function handle(): void
    {
        if (config('services.autocode.disabled')) {
            return;
        }

        $this->client = resolve(InfiniteInterface::class);

        // 1 - Find players that have GT matched
        $players = $this->matchup->matchupTeams->map(function (MatchupTeam $matchupTeam) {
            return $matchupTeam->players;
        })->flatten();
        $playerIds = $players->pluck('id');

        // 2 - Load their custom history to have latest
        $players->each(function (Player $player) {
            $this->client?->matches($player, Mode::CUSTOM());
        });

        // 3 - Pick a player with visible customs to iterate
        $players->each(function (Player $player) use ($playerIds) {
            $player
                ->games()
                ->with('players')
                ->whereDoesntHave('playlist')
                // @phpstan-ignore-next-line
                ->where(function (Builder $query): void {
                    $query
                        ->whereDate('occurred_at', $this->matchup->started_at->subDay())
                        ->orWhereDate('occurred_at', $this->matchup->started_at)
                        ->orWhereDate('occurred_at', $this->matchup->started_at->addDay());
                })
                ->cursor()
                ->filter(function (Game $game) use ($playerIds) {
                    // 4 - Find matches that fit criteria of all step 1 folks
                    $gamePlayerIds = $game->players->pluck('player_id');
                    $diffPlayerIds = array_diff($playerIds->toArray(), $gamePlayerIds->toArray());

                    return count($diffPlayerIds) === 0 && ! in_array($game->id, $this->processedGameIds);
                })
                ->each(function (Game $game) {
                    /** @var MatchupGame $matchupGame */
                    $matchupGame = MatchupGame::query()
                        ->firstOrNew([
                            'game_id' => $game->id,
                            'matchup_id' => $this->matchup->id
                        ]);

                    $matchupGame->game()->associate($game);
                    $matchupGame->matchup()->associate($this->matchup);
                    $matchupGame->saveOrFail();

                    $this->processedGameIds[] = $game->id;
                });
        });
    }
}
