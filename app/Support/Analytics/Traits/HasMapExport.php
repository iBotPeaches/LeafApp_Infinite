<?php

declare(strict_types=1);

namespace App\Support\Analytics\Traits;

use App\Models\Map;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait HasMapExport
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
        return $collection?->map(function (Map $map) {
            return [
                'map' => $map->name,
                $map->{$this->property()},
            ];
        })->toArray() ?? [];
    }
}
