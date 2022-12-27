<?php
declare(strict_types = 1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Enums\Mode;
use App\Models\Analytic;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BasePlayerStat;
use Illuminate\Database\Eloquent\Collection;

class BestKDAServiceRecord extends BasePlayerStat implements AnalyticInterface
{
    public function title(): string
    {
        return 'Best KDA (1k game min)';
    }

    public function key(): string
    {
        return AnalyticKey::BEST_KDA_SR->value;
    }

    public function unit(): string
    {
        return ' KDA';
    }

    public function property(): string
    {
        return 'kda';
    }

    public function displayProperty(Analytic $analytic): string
    {
        return number_format($analytic->value, 2);
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

    public function results(int $limit = 10): ?Collection
    {
        return $this->builder()
            ->select('service_records.*')
            ->with(['player'])
            ->leftJoin('players', 'players.id', '=', 'service_records.player_id')
            ->where('is_cheater', false)
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_number')
            ->where('total_matches', '>=', 1000)
            ->orderByDesc($this->property())
            ->limit($limit)
            ->get();
    }
}
