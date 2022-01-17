<?php

namespace App\Console\Commands;

use App\Models\GamePlayer;
use Illuminate\Console\Command;
use League\Csv\Writer;

class ExportBlogData extends Command
{
    protected $signature = 'app:export-blog-data';
    protected $description = 'Exports Records for me';

    protected array $header = [
        'Date',
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

    public function handle(): int
    {
        GamePlayer::query()
            ->with([
                'game.map',
                'game.category',
                'game.playlist',
            ])
            ->join('games', 'games.id', '=', 'game_players.game_id')
            ->join('playlists', 'playlists.id', '=', 'games.playlist_id')
            ->where('player_id', 1)
            ->where('was_at_end', 1)
            ->where('playlists.is_ranked', 1)
            ->orderBy('games.occurred_at')
            ->cursor()
            ->each(function (GamePlayer $gamePlayer) {
                $perfectMedal = $gamePlayer->hydrated_medals->firstWhere('name', 'Perfect');

                $this->data[] = [
                    $gamePlayer->game->occurred_at->toDateTimeString(),
                    $gamePlayer->game->map->name,
                    $gamePlayer->game->category->name,
                    $gamePlayer->game->playlist->name,
                    $gamePlayer->game->playlist->input?->description,
                    $gamePlayer->game->playlist->queue?->description,
                    $gamePlayer->pre_csr,
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

        $csv = Writer::createFromString();
        $csv->insertOne($this->header);
        $csv->insertAll($this->data);

        echo $csv->toString();

        return self::SUCCESS;
    }
}
