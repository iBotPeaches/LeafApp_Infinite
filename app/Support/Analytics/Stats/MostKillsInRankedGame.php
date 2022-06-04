<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Models\GamePlayer;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseGameStat;
use Illuminate\Database\Eloquent\Model;

class MostKillsInRankedGame extends BaseGameStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Most Kills in Ranked Game';
    }

    public function unit(): string
    {
        return 'kills';
    }

    public function property(Model $model): string
    {
        return number_format($model->kills);
    }

    public function result(): ?GamePlayer
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->builder()
            ->leftJoin('games', 'game_players.game_id', '=', 'games.id')
            ->leftJoin('playlists', 'games.playlist_id', '=', 'playlists.id')
            ->where('playlists.is_ranked', true)
            ->orderByDesc('kills')
            ->first();
    }
}
