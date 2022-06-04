<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\Mode;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use Illuminate\Database\Eloquent\Model;

class MostMedalsServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Most Medals';
    }

    public function unit(): string
    {
        return 'medals';
    }

    public function property(Model $model): string
    {
        return number_format($model->medal_count);
    }

    public function result(): ?ServiceRecord
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->builder()
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_number')
            ->orderByDesc('medal_count')
            ->first();
    }
}
