<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static ATTRITION()
 * @method static static CTF()
 * @method static static ELIMINATION()
 * @method static static ESCALATION()
 * @method static static EXTRACTION()
 * @method static static INFECTION()
 * @method static static KOTH()
 * @method static static LAND_GRAB()
 * @method static static LSS()
 * @method static static ODDBALL()
 * @method static static SLAYER()
 * @method static static STOCKPILE()
 * @method static static STRONGHOLDS()
 * @method static static TOTAL_CONTROL()
 */
final class BaseGametype extends Enum implements LocalizedEnum
{
    const ATTRITION = 1;

    const CTF = 2;

    const ELIMINATION = 3;

    const ESCALATION = 4;

    const INFECTION = 5;

    const KOTH = 6;

    const LAND_GRAB = 7;

    const LSS = 8;

    const ODDBALL = 9;

    const SLAYER = 10;

    const STOCKPILE = 11;

    const STRONGHOLDS = 12;

    const TOTAL_CONTROL = 13;

    const EXTRACTION = 14;
}
