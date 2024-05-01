<?php

declare(strict_types=1);

namespace App\Support\Csr;

use Illuminate\Support\Str;

class Csr
{
    public readonly ?int $value;

    public readonly ?int $tier;

    public readonly string $rank;

    public readonly string $title;

    public function __construct(?int $value, ?int $tier, string $rank)
    {
        $this->value = $value;
        $this->tier = ($rank === 'Unranked' && $tier === 5) ? null : $tier;
        $this->rank = $rank;
        $this->title = trim($rank.' '.($rank !== 'Unranked' ? $tier : ''));
    }

    public function isDifferent(Csr $csr): bool
    {
        return $this->rank !== $csr->rank;
    }

    public function url(): string
    {
        $assetName = trim(Str::lower($this->rank).'-'.$this->tier, '-');

        return asset('/images/csrs/'.$assetName.'.png');
    }
}
