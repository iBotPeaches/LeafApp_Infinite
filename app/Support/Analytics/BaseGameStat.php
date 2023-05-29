<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\GamePlayer;
use App\Models\Season;
use Illuminate\Database\Eloquent\Builder;

class BaseGameStat
{
    public function __construct(protected readonly ?Season $season = null)
    {
        //
    }

    public function type(): AnalyticType
    {
        return AnalyticType::GAME();
    }

    public function builder(): Builder
    {
        return GamePlayer::query();
    }
}
