<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static CURRENT()
 * @method static static SEASON()
 * @method static static ALL_TIME()
 */
final class CompetitiveMode extends Enum implements LocalizedEnum
{
    const CURRENT = 1;
    const SEASON = 2;
    const ALL_TIME = 3;
}
