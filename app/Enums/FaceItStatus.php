<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Str;

/**
 * @method static static FINISHED()
 */
final class FaceItStatus extends Enum implements LocalizedEnum
{
    const UNKNOWN = 0;

    const FINISHED = 1;

    const STARTED = 2;

    const CANCELLED = 3;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match (Str::lower($enumKeyOrValue)) {
            'finished' => self::FINISHED,
            'started' => self::STARTED,
            'cancelled' => self::CANCELLED,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }
}
