<?php

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Playlist;
use App\Models\PlaylistStat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        });
    }
}
