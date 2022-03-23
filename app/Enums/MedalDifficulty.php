<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static LEGENDARY()
 * @method static static MYTHIC()
 * @method static static HEROIC()
 * @method static static NORMAL()
 */
final class MedalDifficulty extends Enum implements LocalizedEnum
{
    const LEGENDARY = 1;
    const MYTHIC = 2;
    const HEROIC = 3;
    const NORMAL = 4;
}
