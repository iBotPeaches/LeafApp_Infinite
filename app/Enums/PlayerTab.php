<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OVERVIEW()
 * @method static static COMPETITIVE()
 * @method static static MATCHES()
 * @method static static CUSTOM()
 */
final class PlayerTab extends Enum implements LocalizedEnum
{
    const OVERVIEW = 'overview';
    const COMPETITIVE = 'competitive';
    const MATCHES = 'matches';
    const CUSTOM = 'custom';
}
