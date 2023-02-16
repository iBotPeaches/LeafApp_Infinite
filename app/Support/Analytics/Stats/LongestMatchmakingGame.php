<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Models\Game;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseOnlyGameStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasGameExport;
use Illuminate\Database\Eloquent\Collection;

class LongestMatchmakingGame extends BaseOnlyGameStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasGameExport;

    public function title(): string
    {
        return 'Longest Matchmaking Game';
    }

    public function key(): string
    {
        return AnalyticKey::LONGEST_MATCHMAKING_GAME->value;
    }

    public function unit(): string
    {
        return 'duration';
    }

    public function property(): string
    {
        return 'duration_seconds';
    }

    public function displayProperty(Analytic $analytic): string
    {
        $game = new Game([
            'duration_seconds' => $analytic->value,
        ]);

        return $game->duration;
    }

    public function results(int $limit = 10): ?Collection
    {
        return $this->builder()
            ->whereNotNull('playlist_id')
            ->orderByDesc($this->property())
            ->limit($limit)
            ->get();
    }
}
