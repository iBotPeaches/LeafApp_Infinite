<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Str;

/**
 * @method static static CROSSPLAY()
 * @method static static CONTROLLER()
 * @method static static KBM()
 */
final class Input extends Enum implements LocalizedEnum
{
    const CROSSPLAY = 0;

    const CONTROLLER = 1;

    const KBM = 2;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        // The HaloDotIP uses 'mnk', I prefer 'kbm'
        if ($enumKeyOrValue === 'mnk') {
            $enumKeyOrValue = 'kbm';
        }

        $enumKeyOrValue = Str::upper(str_replace('-', '_', $enumKeyOrValue));

        return parent::coerce($enumKeyOrValue);
    }
}
