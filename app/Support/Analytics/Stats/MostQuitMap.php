<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Outcome;
use App\Models\Analytic;
use App\Models\Category;
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

    private const LAST_SPARTAN_STANDING_CATEGORY_UUID = '3fdb396febedc607ddd3416aea2ff5a3';

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
            ->whereNotNull('games.playlist_id')
            ->groupBy(['map_id', 'outcome']);

        /** @var Category $lssCategory */
        $lssCategory = Category::query()->where('uuid', self::LAST_SPARTAN_STANDING_CATEGORY_UUID)->first();
        if ($lssCategory) {
            $mapOutcomesQuery->whereNot('games.category_id', '=', $lssCategory->id);
        }

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
