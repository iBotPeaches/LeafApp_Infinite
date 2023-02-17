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

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match (Str::lower($enumKeyOrValue)) {
            'finished' => self::FINISHED,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }
}
