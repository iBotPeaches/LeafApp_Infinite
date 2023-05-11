<?php

declare(strict_types=1);

namespace App\Support\Rotations;

use Illuminate\Support\Collection;

class RotationDecorator
{
    public Collection $rotations;

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
    }
}
