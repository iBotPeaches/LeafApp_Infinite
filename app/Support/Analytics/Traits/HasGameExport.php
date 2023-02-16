<?php

declare(strict_types=1);

namespace App\Support\Analytics\Traits;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait HasGameExport
{
    public function csvHeader(): array
    {
        return [
            'Game',
            'GameLink',
            'Date',
            Str::title($this->property()),
        ];
    }

    public function csvData(?Collection $collection): array
    {
        // @phpstan-ignore-next-line
        return $collection?->map(function (Game $game) {
            return [
                'game' => $game->name,
                'gameLink' => route('game', $game),
                'date' => $game->occurred_at,
                $game->{$this->property()},
            ];
        })->toArray() ?? [];
    }
}
