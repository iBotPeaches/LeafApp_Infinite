<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Mode;
use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use Illuminate\Database\Eloquent\Collection;

class MostTimePlayedServiceRecord extends BasePlayerStat implements AnalyticInterface
{
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
        return number_format(now()->addSeconds((int)$analytic->value)->diffInHours());
    }

    public function results(): ?Collection
    {
        return $this->builder()
            ->select('service_records.*')
            ->with(['player'])
            ->leftJoin('players', 'players.id', '=', 'service_records.player_id')
            ->where('is_cheater', false)
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_number')
            ->orderByDesc($this->property())
            ->limit(10)
            ->get();
    }
}
