<?php
declare(strict_types = 1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BaseOnlyGameStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::ONLY_GAME();
    }

    public function builder(): Builder
    {
        return Game::query();
    }

    public function csvHeader(): array
    {
        return [
            'Game',
            'GameLink',
            'Date',
            Str::title($this->property()),
        ];
    }

    public function csvData(Collection $collection): array
    {
        return $collection->map(function (Game $game) {
            return [
                'game' => $game->name,
                'gameLink' => route('game', $game),
                'date' => $game->occurred_at,
                $game->{$this->property()}
            ];
        })->toArray();
    }
}
