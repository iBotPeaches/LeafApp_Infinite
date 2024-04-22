<?php

declare(strict_types=1);

namespace App\Support\Rotations;

use Illuminate\Support\Collection;

class RotationDecorator
{
    public Collection $rotations;

    public Collection $mapNames;

    public Collection $gametypeNames;

    public function __construct(array $rotations)
    {
        // 343 has 2 types of playlist rotations that come in forms of
        //   Event:{Gametype} on {Map}
        //   Arena:{Gametype} on {Map}
        // This decorator will group the rotations by map and gametype
        // regardless of the prefix.
        $this->rotations = collect($rotations)
            ->map(function ($result) {
                return new RotationResult($result);
            })
            ->groupBy('mapName')
            ->map(function ($groupedRotations) {
                /** @var RotationResult $firstRotation */
                $firstRotation = $groupedRotations->first();
                $totalWeight = $groupedRotations->sum('weight');

                return new RotationResult([
                    'name' => $firstRotation->gametypeName.' on '.$firstRotation->mapName,
                    'weight' => $totalWeight,
                ]);
            })
            ->values();

        $totalWeight = $this->rotations->sum('weight');

        $this->rotations->each(function (RotationResult $result) use ($totalWeight) {
            $result->setWeight($totalWeight);
        });

        $this->mapNames = $this->rotations->groupBy('mapName')->map(function (Collection $result) {
            return $result->sum('weightPercent');
        });

        $this->gametypeNames = $this->rotations->groupBy('gametypeName')->map(function (Collection $result) {
            return $result->sum('weightPercent');
        });
    }
}
