<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\Season;
use App\Models\ServiceRecord;
use Illuminate\Database\Eloquent\Builder;

class BasePlayerStat
{
    public function __construct(protected readonly ?Season $season = null)
    {
        //
    }

    public function type(): AnalyticType
    {
        return AnalyticType::PLAYER();
    }

    public function builder(): Builder
    {
        return ServiceRecord::query();
    }
}
