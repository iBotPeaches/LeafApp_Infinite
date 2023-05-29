<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\Game;
use App\Models\Season;
use Illuminate\Database\Eloquent\Builder;

class BaseOnlyGameStat
{
    public function __construct(protected readonly ?Season $season = null)
    {
        //
    }

    public function type(): AnalyticType
    {
        return AnalyticType::ONLY_GAME();
    }

    public function builder(): Builder
    {
        return Game::query();
    }
}
