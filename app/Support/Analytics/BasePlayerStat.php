<?php
declare(strict_types = 1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\ServiceRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BasePlayerStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::PLAYER();
    }

    public function builder(): Builder
    {
        return ServiceRecord::query();
    }

    public function csvHeader(): array
    {
        return [
            'Gamertag',
            'Profile',
            Str::title($this->property()),
        ];
    }

    public function csvData(Collection $collection): array
    {
        return $collection->map(function (ServiceRecord $serviceRecord) {
            return [
                'gamertag' => $serviceRecord->player->gamertag,
                'profile' => route('player', $serviceRecord->player),
                $serviceRecord->{$this->property()}
            ];
        })->toArray();
    }
}
