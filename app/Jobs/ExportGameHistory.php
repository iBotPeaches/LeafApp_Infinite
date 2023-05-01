<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Experience;
use App\Enums\PlayerTab;
use App\Models\GamePlayer;
use App\Models\Medal;
use App\Models\Player;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportGameHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public static array $header = [
        'Date',
        'Player',
        'MatchId',
        'Map',
        'Category',
        'Playlist',
        'Input',
        'Queue',
        'Csr',
        'Mmr',
        'Outcome',
        'Accuracy',
        'DamageDone',
        'DamageTaken',
        'ShotsFired',
        'ShotsLanded',
        'ShotsMissed',
        'KD',
        'KDA',
        'Kills',
        'Deaths',
        'Assists',
        'Betrayals',
        'Suicides',
        'ExpectedKills',
        'ExpectedDeaths',
        'Score',
        'Perfects',
        'Medals',
        'LengthSeconds',
    ];

    protected array $data = [];

    public Player $player;

    public string $playerTab;

    public function __construct(Player $player, string $playerTab)
    {
        $this->player = $player;
        $this->playerTab = $playerTab;
    }

    public function handle(): array
    {
        GamePlayer::$medalCache = Medal::all();

        $query = GamePlayer::query()
            ->select('game_players.*')
            ->with([
                'player',
                'game.map',
                'game.category',
                'game.playlist',
            ])
            ->join('games', 'games.id', '=', 'game_players.game_id')
            ->leftJoin('playlists', 'playlists.id', '=', 'games.playlist_id')
            ->where('player_id', '=', $this->player->id);

        // Swap type of matches exported based on type
        switch ($this->playerTab) {
            case PlayerTab::OVERVIEW:
            case PlayerTab::COMPETITIVE:
            case PlayerTab::MEDALS:
            case PlayerTab::MATCHES:
                $query->whereNotNull('games.playlist_id');
                break;

            case PlayerTab::CUSTOM:
                $query->where('games.experience', Experience::CUSTOM);
                $query->where(function (Builder $query) {
                    $query
                        ->where('is_lan', '=', false)
                        ->orWhereNull('is_lan');
                });
                break;

            case PlayerTab::LAN:
                $query->where('games.experience', Experience::CUSTOM);
                $query->where('games.is_lan', true);
                break;
        }

        $query
            ->orderBy('games.occurred_at')
            ->cursor()
            ->each(function (GamePlayer $gamePlayer) {
                $perfectMedal = $gamePlayer->hydrated_medals->firstWhere('name', 'Perfect');

                $this->data[] = [
                    $gamePlayer->game->occurred_at->toDateTimeString(),
                    $gamePlayer->player->gamertag,
                    $gamePlayer->game->uuid,
                    $gamePlayer->game->map->name,
                    $gamePlayer->game->gamevariant?->name ?? $gamePlayer->game->category?->name,
                    $gamePlayer->game->playlist?->name,
                    $gamePlayer->game->playlist?->input?->description,
                    $gamePlayer->game->playlist?->queue?->description,
                    $gamePlayer->pre_csr ?? 0,
                    $gamePlayer->mmr,
                    $gamePlayer->outcome->description,
                    $gamePlayer->accuracy,
                    $gamePlayer->damage_dealt,
                    $gamePlayer->damage_taken,
                    $gamePlayer->shots_fired,
                    $gamePlayer->shots_landed,
                    $gamePlayer->shots_missed,
                    $gamePlayer->kd,
                    $gamePlayer->kda,
                    $gamePlayer->kills,
                    $gamePlayer->deaths,
                    $gamePlayer->assists,
                    $gamePlayer->betrayals,
                    $gamePlayer->suicides,
                    $gamePlayer->expected_kills,
                    $gamePlayer->expected_deaths,
                    $gamePlayer->getRawOriginal('score'),
                    $perfectMedal->count ?? 0,
                    $gamePlayer->medal_count,
                    $gamePlayer->game->duration_seconds,
                ];
            });

        GamePlayer::$medalCache = null;

        return $this->data;
    }
}
