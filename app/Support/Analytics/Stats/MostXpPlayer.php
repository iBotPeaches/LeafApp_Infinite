<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseOnlyPlayerStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasPlayerExport;
use Illuminate\Database\Eloquent\Collection;

class MostXpPlayer extends BaseOnlyPlayerStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasPlayerExport;

    public function title(): string
    {
        return 'Most XP';
    }

    public function key(): string
    {
        return AnalyticKey::MOST_XP->value;
    }

    public function unit(): string
    {
        return ' xp';
    }

    public function property(): string
    {
        return 'xp';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value);
    }

    public function results(int $limit = 10): ?Collection
    {
        return $this->builder()
            ->where('is_cheater', false)
            ->orderByDesc($this->property())
            ->limit($limit)
            ->get();
    }
}
