<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\Mode;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use Illuminate\Database\Eloquent\Model;

class BestKDAServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Best KDA (50 game min)';
    }

    public function unit(): string
    {
        return ' KDA';
    }

    public function property(Model $model): string
    {
        return number_format($model->kda, 2);
    }

    public function result(): ?ServiceRecord
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->builder()
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_number')
            ->where('total_matches', '>=', 50)
            ->orderByDesc('kda')
            ->first();
    }
}
