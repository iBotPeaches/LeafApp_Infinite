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
 * @method static static PAUSED()
 * @method static static JOIN()
 * @method static static CREATED()
 * @method static static ADJUSTMENT()
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

    const PAUSED = 7;

    const JOIN = 8;

    const CREATED = 9;

    const ADJUSTMENT = 10;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match (Str::lower($enumKeyOrValue)) {
            'finished' => self::FINISHED,
            'started' => self::STARTED,
            'cancelled' => self::CANCELLED,
            'manual_result', 'manual-result' => self::MANUAL_RESULT,
            'scheduling' => self::SCHEDULING,
            'scheduled' => self::SCHEDULED,
            'paused' => self::PAUSED,
            'join' => self::JOIN,
            'created' => self::CREATED,
            'adjustment' => self::ADJUSTMENT,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }
}
