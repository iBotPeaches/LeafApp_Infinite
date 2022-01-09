<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static MODE()
 * @method static static MULTIKILL()
 * @method static static PROFICIENCY()
 * @method static static SKILL()
 * @method static static STYLE()
 * @method static static UNKNOWN()
 */
final class MedalCategory extends Enum implements LocalizedEnum
{
    const MODE = 1;
    const MULTIKILL = 2;
    const PROFICIENCY = 3;
    const SKILL = 4;
    const STYLE = 5;
    const UNKNOWN = 6;
}
