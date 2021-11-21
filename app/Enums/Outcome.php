<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static WIN()
 * @method static static LOSS()
 * @method static static LEFT()
 */
final class Outcome extends Enum
{
    const WIN = 1;
    const LOSS = 2;
    const LEFT = 3;
}
