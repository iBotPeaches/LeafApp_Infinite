<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Mode;
use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use Illuminate\Database\Eloquent\Collection;

class MostBetrayalsServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Most Betrayals';
    }

    public function key(): string
    {
        return AnalyticKey::MOST_BETRAYALS_SR->value;
    }

    public function unit(): string
    {
        return 'betrayals';
    }

    public function property(): string
    {
        return 'betrayals';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value);
    }

    public function results(): ?Collection
    {
        return $this->builder()
            ->with(['player'])
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_number')
            ->orderByDesc($this->property())
            ->limit(10)
            ->get();
    }
}
