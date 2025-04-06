<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\OverviewStat;
use Illuminate\Database\Eloquent\Builder;

class BaseOverviewStatStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::OVERVIEW_STAT();
    }

    public function baseBuilder(): Builder
    {
        return OverviewStat::query();
    }
}
