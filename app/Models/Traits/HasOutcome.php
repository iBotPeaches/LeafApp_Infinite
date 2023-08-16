<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\Outcome;
use Illuminate\Support\Str;

use function is_numeric;

trait HasOutcome
{
    public function setOutcomeAttribute(string $value): void
    {
        $outcome = is_numeric($value) ? Outcome::fromValue((int) $value) : Outcome::coerce(Str::upper($value));
        if (empty($outcome)) {
            throw new \InvalidArgumentException('Invalid Outcome Enum ('.$value.')');
        }

        $this->attributes['outcome'] = $outcome->value;
    }
}
