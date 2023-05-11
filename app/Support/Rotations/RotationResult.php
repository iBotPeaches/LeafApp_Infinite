<?php

declare(strict_types=1);

namespace App\Support\Rotations;

use App\Models\Category;
use App\Models\Map;
use Illuminate\Support\Arr;

class RotationResult
{
    public string $name;

    public int $weight;

    public ?float $weightPercent;

    public ?Category $category;

    public ?Map $map;

    public function __construct(array $data)
    {
        $this->name = Arr::get($data, 'name');
        $this->weight = (int) Arr::get($data, 'weight', 0);
    }

    public function setWeight(int $total): void
    {
        if ($total > 0) {
            $this->weightPercent = ($this->weight / $total) * 100;
        }
    }
}
