<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Medal;
use Illuminate\Support\Collection;

/**
 * @property-read Collection $hydrated_medals
 */
trait HasMedals
{
    public function getHydratedMedalsAttribute(): Collection
    {
        $medals = $this->medals;

        return Medal::all()->map(function (Medal $medal) use ($medals) {
            $medal['count'] = $medals[$medal->id] ?? 0;
            return $medal;
        })->reject(function (Medal $medal) {
            // @phpstan-ignore-next-line
            return $medal->count === 0;
        })->sortByDesc('count');
    }
}
