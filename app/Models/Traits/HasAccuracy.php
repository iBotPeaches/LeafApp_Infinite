<?php

declare(strict_types=1);

namespace App\Models\Traits;

/**
 * @property-read string $accuracy_color
 */
trait HasAccuracy
{
    public function getAccuracyColorAttribute(): string
    {
        switch (true) {
            case $this->accuracy > 55:
                return 'success';

            case $this->accuracy > 40 && $this->accuracy <= 55:
                return '';

            default:
            case $this->accuracy < 40:
                return 'danger';
        }
    }
}
