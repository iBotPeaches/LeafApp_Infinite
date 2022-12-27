<?php
declare(strict_types = 1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\GamePlayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

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

    public function csvHeader(): array
    {
        return [
            'Gamertag',
            'Profile',
            'Game',
            'GameLink',
            'Date',
            Str::title($this->property()),
        ];
    }

    public function csvData(Collection $collection): array
    {
        return $collection->map(function (GamePlayer $gamePlayer) {
            return [
                'gamertag' => $gamePlayer->player->gamertag,
                'profile' => route('player', $gamePlayer->player),
                'game' => $gamePlayer->game->name,
                'gameLink' => route('game', $gamePlayer->game),
                'date' => $gamePlayer->game->occurred_at,
                $gamePlayer->{$this->property()}
            ];
        })->toArray();
    }
}
