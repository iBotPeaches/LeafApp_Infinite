<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\Outcome;
use function number_format;

trait HasScoring
{
    public function getScoreAttribute(int $value): string
    {
        return number_format($value);
    }

    public function getVictoryColor(): string
    {
        if ($this->outcome->is(Outcome::WIN())) {
            return 'has-background-success-light';
        }

        if ($this->outcome->is(Outcome::LOSS())) {
            return 'has-background-warning-light';
        }

        if ($this->outcome->is(Outcome::LEFT())) {
            return 'has-background-danger-light';
        }

        return '';
    }
}
