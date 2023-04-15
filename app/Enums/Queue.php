<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OPEN()
 * @method static static SOLO_DUO()
 */
final class Queue extends Enum implements LocalizedEnum
{
    const OPEN = 0;

    const SOLO_DUO = 1;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match (strtolower($enumKeyOrValue)) {
            'open', 'open-queue' => self::OPEN,
            'solo-duo' => self::SOLO_DUO,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }
}
