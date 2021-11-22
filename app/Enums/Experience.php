<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static BTB()
 * @method static static ARENA()
 */
final class Experience extends Enum implements LocalizedEnum
{
    const BTB = 1;
    const ARENA = 2;
}
