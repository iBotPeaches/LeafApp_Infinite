<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Outcome;
use App\Models\Analytic;
use App\Models\Game;
use App\Models\Gamevariant;
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
        return 'percent_quit';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value, 2).'%';
    }

    public function results(int $limit = 10): ?Collection
    {
        $lssVariants = Gamevariant::query()
            ->where('name', '=', 'Last Spartan Standing')
            ->pluck('id');

        $mapOutcomesQuery = Game::query()
            ->selectRaw('maps.name as map_name, outcome, count(*) as total')
            ->join('game_players', 'games.id', '=', 'game_players.game_id', 'right')
            ->join('maps', 'maps.id', '=', 'games.map_id', 'right')
            ->whereNotNull('games.playlist_id')
            ->whereNotIn('games.gamevariant_id', $lssVariants)
            ->groupBy(['map_name', 'outcome']);

        return $this->builder()
            ->selectRaw('map_name, outcome, total, (total / (sum(total) over (partition by map_name))) * 100 as '.$this->property())
            ->from($mapOutcomesQuery)
            ->where('outcome', '=', Outcome::LEFT)
            ->orderByDesc($this->property())
            ->orderBy('map_name')
            ->get();
    }
}
