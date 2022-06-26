<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Mode;
use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use Illuminate\Database\Eloquent\Collection;

class BestKDServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Best KD (50 game min)';
    }

    public function key(): string
    {
        return AnalyticKey::BEST_KD_SR->value;
    }

    public function unit(): string
    {
        return ' KD';
    }

    public function property(): string
    {
        return 'kd';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value, 2);
    }

    public function results(): ?Collection
    {
        return $this->builder()
            ->with(['player'])
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_number')
            ->where('total_matches', '>=', 50)
            ->orderByDesc($this->property())
            ->limit(10)
            ->get();
    }
}
