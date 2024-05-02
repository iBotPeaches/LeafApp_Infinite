<?php

declare(strict_types=1);

namespace App\Support\Analytics\Traits;

use App\Models\OverviewStat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait HasOverviewStatExport
{
    public function csvHeader(): array
    {
        return [
            'Map',
            Str::title($this->property()),
        ];
    }

    public function csvData(?Collection $collection): array
    {
        // @phpstan-ignore-next-line
        return $collection?->map(function (OverviewStat $overviewStat) {
            return [
                'map' => $overviewStat->getAttribute('label'),
                $overviewStat->{$this->property()},
            ];
        })->toArray() ?? [];
    }
}
