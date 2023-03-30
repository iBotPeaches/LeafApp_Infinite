<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Outcome;
use App\Models\Analytic;
use App\Models\Game;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseMapStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasMapExport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MostQuitMap extends BaseMapStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasMapExport;

    private const LAST_SPARTAN_STANDING_CATEGORY_ID = '3fdb396febedc607ddd3416aea2ff5a3';

    public function title(): string
    {
        return 'Most Quit/Crashed Map';
    }

    public function key(): string
    {
        return AnalyticKey::MOST_QUIT_MAP->value;
    }

    public function unit(): string
    {
        return 'quit';
    }

    public function property(): string
    {
        return 'fraction_quit';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value, 2).'%';
    }

    public function results(int $limit = 10): ?Collection
    {
        $mapOutcomesQuery = Game::query()
            ->selectRaw('map_id, outcome, count(*) as total')
            ->join('game_players', 'games.id', '=', 'game_players.game_id', 'right')
            ->join('categories', 'games.category_id', '=', 'categories.id', 'left')
            ->whereNotNull('games.playlist_id')
            ->whereNot('categories.uuid', '=', self::LAST_SPARTAN_STANDING_CATEGORY_ID)
            ->groupBy(['map_id', 'outcome']);

        $outcomeFractionQuery = DB::query()
            ->selectRaw('map_id, outcome, total, (total / (sum(total) over (partition by map_id))) * 100 as '.$this->property())
            ->from($mapOutcomesQuery);

        return $this->builder()
            ->selectRaw('maps.*, '.$this->property())
            ->joinSub($outcomeFractionQuery, 'outcome_fraction', 'map_id', '=', 'id', 'right')
            ->where('outcome', '=', Outcome::LEFT)
            ->orderByDesc($this->property())
            ->limit($limit)
            ->get();
    }
}
