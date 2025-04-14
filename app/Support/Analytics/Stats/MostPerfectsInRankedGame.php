<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Models\GamePlayer;
use App\Models\PlaylistAnalytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseGameStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasGamePlayerExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MostPerfectsInRankedGame extends BaseGameStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasGamePlayerExport;

    public function title(): string
    {
        return 'Most Perfects in Ranked Game';
    }

    public function key(): string
    {
        return AnalyticKey::MOST_PERFECTS_RANKED_GAME->value;
    }

    public function unit(): string
    {
        return 'perfects';
    }

    public function property(): string
    {
        return 'medals[\'1512363953\']';
    }

    public function propertyFn(GamePlayer $gamePlayer): float
    {
        return (float) Arr::get($gamePlayer, 'medals.1512363953', 0);
    }

    public function displayProperty(Analytic|PlaylistAnalytic $analytic): string
    {
        return number_format($analytic->value);
    }

    public function resultBuilder(): Builder
    {
        return $this->baseBuilder()
            ->select('game_players.*', DB::raw('CAST(JSON_EXTRACT(medals, "$.1512363953") as unsigned) as value'))
            ->with(['game', 'player'])
            ->leftJoin('players', 'players.id', '=', 'game_players.player_id')
            ->leftJoin('games', 'game_players.game_id', '=', 'games.id')
            ->leftJoin('playlists', 'games.playlist_id', '=', 'playlists.id')
            ->where('players.is_cheater', false)
            ->where('players.is_bot', false)
            ->where('players.is_botfarmer', false)
            ->whereRaw('CAST(JSON_EXTRACT(medals, "$.1512363953") as unsigned) > 0')
            ->orderByDesc('value')
            ->orderByDesc('id');
    }

    public function results(int $limit = 10): ?Collection
    {
        return $this->resultBuilder()
            ->where('playlists.is_ranked', true)
            ->limit($limit)
            ->get();
    }
}
