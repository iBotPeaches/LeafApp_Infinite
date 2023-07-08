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

class MostTimePlayedServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasServiceRecordExport;

    public function title(): string
    {
        return 'Most Time Played';
    }

    public function key(): string
    {
        return AnalyticKey::MOST_TIME_PLAYED_SR->value;
    }

    public function unit(): string
    {
        return ' hours';
    }

    public function property(): string
    {
        return 'total_seconds_played';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format(now()->addSeconds((int) $analytic->value)->diffInHours());
    }

    public function results(int $limit = 10): ?Collection
    {
        return $this->builder()
            ->select('service_records.*')
            ->with(['player'])
            ->leftJoin('players', 'players.id', '=', 'service_records.player_id')
            ->where('is_cheater', false)
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_key')
            ->orderByDesc($this->property())
            ->limit($limit)
            ->get();
    }
}
