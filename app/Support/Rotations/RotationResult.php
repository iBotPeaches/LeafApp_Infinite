<?php

declare(strict_types=1);

namespace App\Support\Rotations;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RotationResult
{
    public string $name;

    public int $weight;

    public ?float $weightPercent;

    public string $mapName;

    public string $gametypeName;

    public function __construct(array $data)
    {
        $this->name = Arr::get($data, 'name');
        $this->weight = (int) Arr::get($data, 'weight', 0);

        // Support parsing "Arena:Team Slayer on Argyle" into [Team Slayer][Argyle]
        [$gametypeName, $mapName] = explode(' on ', $this->name);

        $this->gametypeName = Str::after($gametypeName, ':');
        if (Str::contains($gametypeName, 'Super') && ! Str::contains($this->gametypeName, 'Super')) {
            $this->gametypeName .= ' (Super)';
        }
        $this->mapName = $mapName;
    }

    public function setWeight(int $total): void
    {
        if ($total > 0) {
            $this->weightPercent = ($this->weight / $total) * 100;
        }
    }
}
