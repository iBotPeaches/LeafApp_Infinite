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
        $this->rotations = collect($rotations)
            ->map(function ($result) {
                return new RotationResult($result);
            });

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
