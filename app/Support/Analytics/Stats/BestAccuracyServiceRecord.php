<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\Mode;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use Illuminate\Database\Eloquent\Model;

class BestAccuracyServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Best Accuracy (50 game min)';
    }

    public function unit(): string
    {
        return 'accuracy';
    }

    public function property(Model $model): string
    {
        return number_format($model->accuracy, 2) . '%';
    }

    public function result(): ?ServiceRecord
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->builder()
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_number')
            ->where('total_matches', '>=', 50)
            ->orderByDesc('accuracy')
            ->first();
    }
}
