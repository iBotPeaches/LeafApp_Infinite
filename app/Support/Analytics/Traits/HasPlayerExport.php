<?php

declare(strict_types=1);

namespace App\Support\Analytics\Traits;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait HasPlayerExport
{
    public function csvHeader(): array
    {
        return [
            'Gamertag',
            'Profile',
            Str::title($this->property()),
        ];
    }

    public function csvData(?Collection $collection): array
    {
        // @phpstan-ignore-next-line
        return $collection?->map(function (Player $player) {
            return [
                'gamertag' => $player->gamertag,
                'profile' => route('player', $player),
                $player->{$this->property()},
            ];
        })->toArray() ?? [];
    }
}
