<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static MATCHMAKING()
 * @method static static CUSTOMS()
 */
final class OverviewType extends Enum implements LocalizedEnum
{
    const MATCHMAKING = 'matchmaking';

    const CUSTOMS = 'customs';
}
