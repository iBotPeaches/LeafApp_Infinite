<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;

class BaseOnlyPlayerStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::ONLY_PLAYER();
    }

    public function builder(): Builder
    {
        return Player::query();
    }
}
