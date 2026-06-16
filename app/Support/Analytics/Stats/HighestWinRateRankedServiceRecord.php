<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Mode;
use App\Models\Analytic;
use App\Models\PlaylistAnalytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasServiceRecordExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class HighestWinRateRankedServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasServiceRecordExport;

    public function title(): string
    {
        return 'Highest Ranked Win Rate (1k game min)';
    }

    public function key(): string
    {
        return AnalyticKey::HIGHEST_WIN_RATE_RANKED_SR->value;
    }

    public function unit(): string
    {
        return '% win rate';
    }

    public function property(): string
    {
        return 'win_percent';
    }

    public function displayProperty(Analytic|PlaylistAnalytic $analytic): string
    {
        return number_format($analytic->value, 2).'%';
    }

    public function resultBuilder(): Builder
    {
        return $this->baseBuilder()
            ->select('service_records.*')
            ->with(['player'])
            ->leftJoin('players', 'players.id', '=', 'service_records.player_id')
            ->where('is_cheater', false)
            ->where('is_bot', false)
            ->where('is_botfarmer', false)
            ->where('mode', Mode::MATCHMADE_RANKED)
            ->whereNull('season_key')
            ->where('total_matches', '>=', 1000)
            ->orderByRaw('(matches_won * 1.0 / total_matches) DESC');
    }

    public function results(int $limit = 10): ?Collection
    {
        return $this->resultBuilder()
            ->limit($limit)
            ->get();
    }
}
