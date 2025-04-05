<?php

namespace App\Jobs;

use App\Enums\AnalyticType;
use App\Enums\QueueName;
use App\Models\Analytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Playlist;
use App\Models\PlaylistAnalytic;
use App\Models\PlaylistStat;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\Stats\HighestScoreInRankedGame;
use App\Support\Analytics\Stats\LongestMatchmakingGame;
use App\Support\Analytics\Stats\MostAssistsInGame;
use App\Support\Analytics\Stats\MostDeathsInGame;
use App\Support\Analytics\Stats\MostKillsInGame;
use App\Support\Analytics\Stats\MostKillsWithZeroDeathsGame;
use App\Support\Analytics\Stats\MostMedalsInGame;
use App\Support\Analytics\Stats\MostPerfectsInRankedGame;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProcessPlaylistAnalytic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Playlist $playlist
    ) {
        $this->onQueue(QueueName::RECORDS);
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $this->findPlaylistAnalytics();
            $this->findPlaylistStats();
        });
    }

    private function findPlaylistAnalytics(): void
    {
        $validAnalytics = [
            LongestMatchmakingGame::class,
            HighestScoreInRankedGame::class,
            MostAssistsInGame::class,
            MostDeathsInGame::class,
            MostKillsInGame::class,
            MostKillsWithZeroDeathsGame::class,
            MostMedalsInGame::class,
            MostPerfectsInRankedGame::class,
        ];

        foreach ($validAnalytics as $analyticClass) {
            if (! is_a($analyticClass, AnalyticInterface::class, true)) {
                continue;
            }

            // Borrow the generic result builder from the analytic class and apply specific Playlist
            $analytic = new $analyticClass;
            $builder = $analytic->resultBuilder();

            // Delete existing analytics prior to execution
            PlaylistAnalytic::query()
                ->where('playlist_id', $this->playlist->id)
                ->where('key', $analytic->key())
                ->delete();

            match ($analyticClass) {
                LongestMatchmakingGame::class => $builder
                    ->join('playlists', 'playlists.id', '=', 'games.playlist_id')
                    ->where('playlists.id', $this->playlist->id),
                default => $builder->where('playlists.id', $this->playlist->id),
            };

            $gamePlayers = $builder
                ->limit(3)
                ->get();

            switch ($analytic->type()) {
                case AnalyticType::GAME():
                    /** @var Collection<int, GamePlayer> $gamePlayers */
                    $this->handleGamePlayerResults($analytic, $gamePlayers);
                    break;
                case AnalyticType::ONLY_GAME():
                    /** @var Collection<int, Game> $gamePlayers */
                $this->handleGameResults($analytic, $gamePlayers);
                    break;
            }
        }
    }

    private function findPlaylistStats(): void
    {
        $totalMatches = Game::query()
            ->where('playlist_id', $this->playlist->id)
            ->count('id');

        /** @var array $metadataStats */
        $metadataStats = GamePlayer::query()
            ->join('games', 'games.id', '=', 'game_players.game_id')
            ->join('playlists', 'playlists.id', '=', 'games.playlist_id')
            ->where('playlists.id', $this->playlist->id)
            ->selectRaw('COUNT(player_id) as total_players, COUNT(distinct player_id) as total_distinct_players')
            ->first();

        PlaylistStat::query()
            ->upsert([
                'playlist_id' => $this->playlist->id,
                'total_matches' => $totalMatches,
                'total_players' => Arr::get($metadataStats, 'total_players', 0),
                'total_unique_players' => Arr::get($metadataStats, 'total_distinct_players', 0),
            ], [
                'playlist_id',
            ], [
                'total_matches',
                'total_players',
                'total_unique_players',
            ]);
    }

    /** @param  Collection<int, Game>  $games */
    private function handleGameResults(AnalyticInterface $analytic, Collection $games): void
    {
        $games->each(function (Game $game, int $index) use ($analytic) {
            $playlistAnalytic = new PlaylistAnalytic();
            $playlistAnalytic->key = $analytic->key();
            $playlistAnalytic->place = $index + 1;
            $playlistAnalytic->value = (float) $game->{$analytic->property()};
            $playlistAnalytic->game()->associate($game);
            $playlistAnalytic->save();
        });
    }

    /** @param  Collection<int, GamePlayer>  $gamePlayers */
    private function handleGamePlayerResults(AnalyticInterface $analytic, Collection $gamePlayers): void
    {
        $lastIndex = null;
        $lastValue = null;

        $gamePlayers->each(function (GamePlayer $gamePlayer, int $index) use ($analytic, &$lastIndex, &$lastValue) {
            if (method_exists($analytic, 'propertyFn')) {
                $value = $analytic->propertyFn($gamePlayer);
            } else {
                $value = (float) $gamePlayer->{$analytic->property()};
            }
            $place = $index + 1;
            if ($value === $lastValue) {
                $place = $lastIndex;
            }

            $playlistAnalytic = new PlaylistAnalytic;
            $playlistAnalytic->key = $analytic->key();
            $playlistAnalytic->place = (int) $place;
            $playlistAnalytic->value = $value;
            $playlistAnalytic->game()->associate($gamePlayer->game);
            $playlistAnalytic->player()->associate($gamePlayer->player);
            $playlistAnalytic->playlist()->associate($this->playlist);
            $playlistAnalytic->save();

            // Store our last values in case users share the value.
            $lastIndex = $playlistAnalytic->place;
            $lastValue = $playlistAnalytic->value;
        });
    }
}
