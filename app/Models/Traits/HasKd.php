<?php

declare(strict_types=1);

namespace App\Models\Traits;

use function number_format;

trait HasKd
{
    public function getKdAttribute(float $value): string
    {
        return number_format($value, 2);
    }

    public function getKdaAttribute(float $value): string
    {
        return number_format($value, 2);
    }

    public function getKdColor(): string
    {
        return $this->kd >= 1
            ? 'has-background-success-soft'
            : 'has-background-danger-soft';
    }

    public function getKdaColor(): string
    {
        return $this->kda >= 1
            ? 'has-background-success-soft'
            : 'has-background-danger-soft';
    }
}
