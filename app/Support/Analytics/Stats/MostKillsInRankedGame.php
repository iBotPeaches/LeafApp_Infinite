<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseGameStat;
use Illuminate\Database\Eloquent\Collection;

class MostKillsInRankedGame extends BaseGameStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Most Kills in Ranked Game';
    }

    public function key(): string
    {
        return AnalyticKey::MOST_KILLS_RANKED_GAME->value;
    }

    public function unit(): string
    {
        return 'kills';
    }

    public function property(): string
    {
        return 'kills';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value);
    }

    public function results(): ?Collection
    {
        return $this->builder()
            ->select('game_players.*')
            ->with(['game', 'player'])
            ->leftJoin('players', 'players.id', '=', 'game_players.player_id')
            ->where('players.is_cheater', false)
            ->leftJoin('games', 'game_players.game_id', '=', 'games.id')
            ->leftJoin('playlists', 'games.playlist_id', '=', 'playlists.id')
            ->where('playlists.is_ranked', true)
            ->orderByDesc($this->property())
            ->limit(10)
            ->get();
    }
}
