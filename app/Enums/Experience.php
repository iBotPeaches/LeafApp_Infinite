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
 */
final class Experience extends Enum implements LocalizedEnum
{
    const BTB = 1;
    const ARENA = 2;
    const PVE_BOTS = 3;
    const CUSTOM = 4;
    const FEATURED = 5;

    public static function coerce($enumKeyOrValue): ?Enum
    {
        $enumKeyOrValue = Str::upper(Str::replace('-', '_', $enumKeyOrValue));
        return parent::coerce($enumKeyOrValue);
    }
}
