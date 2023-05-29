<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Mode;
use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasServiceRecordExport;
use Illuminate\Database\Eloquent\Collection;

class BestAccuracyServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    use HasServiceRecordExport;
    use HasExportUrlGeneration;

    public function title(): string
    {
        return 'Best Accuracy (1k game min)';
    }

    public function key(): string
    {
        return AnalyticKey::BEST_ACCURACY_SR->value;
    }

    public function unit(): string
    {
        return 'accuracy';
    }

    public function property(): string
    {
        return 'accuracy';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value, 2).'%';
    }

    public function results(int $limit = 10): ?Collection
    {
        $seasonKey = $this->season?->key;

        $query = $this->builder()
            ->select('service_records.*')
            ->with(['player'])
            ->leftJoin('players', 'players.id', '=', 'service_records.player_id')
            ->where('is_cheater', false)
            ->where('mode', Mode::MATCHMADE_PVP)
            ->where('total_matches', '>=', 1000)
            ->orderByDesc($this->property())
            ->limit($limit);

        if ($seasonKey) {
            $query->where('season_key', $seasonKey);
        } else {
            $query->whereNull('season_key');
        }

        return $query->get();
    }
}
