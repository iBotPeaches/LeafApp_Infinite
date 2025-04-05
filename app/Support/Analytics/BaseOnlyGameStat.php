<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;

class BaseOnlyGameStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::ONLY_GAME();
    }

    public function baseBuilder(): Builder
    {
        return Game::query();
    }
}
