<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseOverviewStatStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasOverviewStatExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MostQuitMap extends BaseOverviewStatStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasOverviewStatExport;

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

    public function resultBuilder(): Builder
    {
        return $this->baseBuilder()
            ->selectRaw('name as label, overview_id, total_dnf, total_players, (total_dnf / total_players * 100) as percent_quit')
            ->join('overviews', 'overview_stats.overview_id', '=', 'overviews.id')
            ->whereNull('overview_gametype_id')
            ->whereNull('overview_map_id')
            ->where('overviews.is_manual', false)
            ->where('total_players', '>', 0)
            ->orderByDesc('percent_quit')
            ->having('percent_quit', '>', 0);
    }

    public function results(int $limit = 10): ?Collection
    {
        return $this->resultBuilder()
            ->limit($limit)
            ->get();
    }
}
