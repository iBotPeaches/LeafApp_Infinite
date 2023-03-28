<?php
declare(strict_types = 1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\Map;
use Illuminate\Database\Eloquent\Builder;

class BaseMapStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::MAP();
    }

    public function builder(): Builder
    {
        return Map::query();
    }
}
