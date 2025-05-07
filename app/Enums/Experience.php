<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Str;

/**
 * @method static static BTB()
 * @method static static ARENA()
 * @method static static PVE_BOTS()
 * @method static static CUSTOM()
 * @method static static FEATURED()
 * @method static static UNKNOWN()
 * @method static static PVE()
 * @method static static UNTRACKED()
 */
final class Experience extends Enum implements LocalizedEnum
{
    const BTB = 1;

    const ARENA = 2;

    const PVE_BOTS = 3;

    const CUSTOM = 4;

    const FEATURED = 5;

    const UNKNOWN = 6;

    const PVE = 7;

    const UNTRACKED = 8;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = Str::upper(str_replace('-', '_', $enumKeyOrValue));

        return parent::coerce($enumKeyOrValue);
    }
}
