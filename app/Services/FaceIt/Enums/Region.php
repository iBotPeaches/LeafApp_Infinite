<?php

declare(strict_types=1);

namespace App\Services\FaceIt\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static NA()
 * @method static static EU()
 * @method static static OCE()
 * @method static static LATAM()
 */
final class Region extends Enum implements LocalizedEnum
{
    const NA = 1;

    const EU = 2;

    const OCE = 3;

    const LATAM = 4;
}
