<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static MODE()
 * @method static static KILLING_SPREE()
 * @method static static PROFICIENCY()
 * @method static static SKILL()
 * @method static static STYLE()
 * @method static static MULTIKILL()
 * @method static static UNKNOWN()
 */
final class MedalType extends Enum implements LocalizedEnum
{
    const MODE = 1;

    const KILLING_SPREE = 2;

    const PROFICIENCY = 3;

    const SKILL = 4;

    const STYLE = 5;

    const MULTIKILL = 6;

    const UNKNOWN = 99;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match ($enumKeyOrValue) {
            'KILLING-SPREE' => self::KILLING_SPREE,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }
}
