<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Str;

/**
 * @method static static UNKNOWN()
 * @method static static FINISHED()
 * @method static static STARTED()
 * @method static static CANCELLED()
 * @method static static MANUAL_RESULT()
 * @method static static SCHEDULING()
 * @method static static SCHEDULED()
 */
final class FaceItStatus extends Enum implements LocalizedEnum
{
    const UNKNOWN = 0;

    const FINISHED = 1;

    const STARTED = 2;

    const CANCELLED = 3;

    const MANUAL_RESULT = 4;

    const SCHEDULING = 5;

    const SCHEDULED = 6;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match (Str::lower($enumKeyOrValue)) {
            'finished' => self::FINISHED,
            'started' => self::STARTED,
            'cancelled' => self::CANCELLED,
            'manual_result', 'manual-result' => self::MANUAL_RESULT,
            'scheduling' => self::SCHEDULING,
            'scheduled' => self::SCHEDULED,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }
}
