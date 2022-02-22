<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Str;

/**
 * @method static static CURRENT()
 * @method static static SEASON()
 * @method static static ALL_TIME()
 */
final class CompetitiveMode extends Enum implements LocalizedEnum
{
    const CURRENT = 1;
    const SEASON = 2;
    const ALL_TIME = 3;

    public static function coerce($enumKeyOrValue): ?Enum
    {
        $enumKeyOrValue = is_numeric($enumKeyOrValue)
            ? $enumKeyOrValue
            : Str::upper(Str::replace('-', '_', $enumKeyOrValue));

        return parent::coerce($enumKeyOrValue);
    }
}
