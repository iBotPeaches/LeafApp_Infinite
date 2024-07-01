<?php

declare(strict_types=1);

namespace App\Services\DotApi\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static EAGLE()
 * @method static static COBRA()
 * @method static static HADES()
 * @method static static VALKYRIE()
 * @method static static RAMPART()
 * @method static static CUTLASS()
 * @method static static VALOR()
 * @method static static HAZARD()
 */
final class Team extends Enum
{
    const EAGLE = 0;

    const COBRA = 1;

    const HADES = 2;

    const VALKYRIE = 3;

    const RAMPART = 4;

    const CUTLASS = 5;

    const VALOR = 6;

    const HAZARD = 7;
}
