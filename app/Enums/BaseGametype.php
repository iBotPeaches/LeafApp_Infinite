<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

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
}
