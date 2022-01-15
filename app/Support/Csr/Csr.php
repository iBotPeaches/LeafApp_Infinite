<?php
declare(strict_types=1);

namespace App\Support\Csr;

class Csr
{
    public ?int $value;
    public string $rank;

    public function __construct(?int $value, ?int $tier, string $rank)
    {
        $this->value = $value;
        $this->rank = trim($rank . ' ' . $tier);
    }

    public function isDifferent(Csr $csr): bool
    {
        return $this->rank !== $csr->rank;
    }
}
