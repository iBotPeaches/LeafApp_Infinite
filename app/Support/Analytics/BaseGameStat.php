<?php
declare(strict_types = 1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\GamePlayer;
use Illuminate\Database\Eloquent\Builder;

class BaseGameStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::GAME();
    }

    public function builder(): Builder
    {
        return GamePlayer::query();
    }
}
