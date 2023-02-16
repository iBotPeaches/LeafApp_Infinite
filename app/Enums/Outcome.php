<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static WIN()
 * @method static static LOSS()
 * @method static static LEFT()
 * @method static static DRAW()
 */
final class Outcome extends Enum implements LocalizedEnum
{
    const WIN = 1;

    const LOSS = 2;

    const LEFT = 3;

    const DRAW = 4;
}
