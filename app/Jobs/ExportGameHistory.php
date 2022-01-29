<?php
declare(strict_types = 1);

namespace App\Jobs;

use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportGameHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public static array $header = [
        'Date',
        'MatchId',
        'Map',
        'Category',
        'Playlist',
        'Input',
        'Queue',
        'Csr',
        'Outcome',
        'Accuracy',
        'DamageDone',
        'DamageTaken',
        'KD',
        'KDA',
        'Score',
        'Perfects',
        'Medals',
    ];

    protected array $data = [];

    public Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function handle(): array
    {
        GamePlayer::query()
            ->with([
                'game.map',
                'game.category',
                'game.playlist',
            ])
            ->join('games', 'games.id', '=', 'game_players.game_id')
            ->join('playlists', 'playlists.id', '=', 'games.playlist_id')
            ->where('player_id', '=', $this->player->id)
            ->orderBy('games.occurred_at')
            ->cursor()
            ->each(function (GamePlayer $gamePlayer) {
                $perfectMedal = $gamePlayer->hydrated_medals->firstWhere('name', 'Perfect');

                $this->data[] = [
                    $gamePlayer->game->occurred_at->toDateTimeString(),
                    $gamePlayer->game->uuid,
                    $gamePlayer->game->map->name,
                    $gamePlayer->game->category->name,
                    $gamePlayer->game->playlist->name,
                    $gamePlayer->game->playlist->input?->description,
                    $gamePlayer->game->playlist->queue?->description,
                    $gamePlayer->pre_csr ?? 0,
                    $gamePlayer->outcome->description,
                    $gamePlayer->accuracy,
                    $gamePlayer->damage_dealt,
                    $gamePlayer->damage_taken,
                    $gamePlayer->kd,
                    $gamePlayer->kda,
                    $gamePlayer->getRawOriginal('score'),
                    $perfectMedal->count ?? 0,
                    $gamePlayer->medal_count
                ];
            });

        return $this->data;
    }
}
