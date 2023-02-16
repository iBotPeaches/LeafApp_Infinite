<?php

declare(strict_types=1);

namespace App\Support\Analytics\Traits;

use App\Models\ServiceRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait HasServiceRecordExport
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
        return $collection?->map(function (ServiceRecord $serviceRecord) {
            return [
                'gamertag' => $serviceRecord->player->gamertag,
                'profile' => route('player', $serviceRecord->player),
                $serviceRecord->{$this->property()},
            ];
        })->toArray() ?? [];
    }
}
