<?php
declare(strict_types=1);

namespace App\Support\Csr;

class Csr
{
    public int $value;
    public string $rank;

    public function __construct(int $value, string $rank)
    {
        $this->value = $value;
        $this->rank = $rank;
    }
}
