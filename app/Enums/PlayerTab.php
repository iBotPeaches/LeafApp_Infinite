<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OVERVIEW()
 * @method static static MEDALS()
 * @method static static COMPETITIVE()
 * @method static static MATCHES()
 * @method static static CUSTOM()
 * @method static static LAN()
 */
final class PlayerTab extends Enum implements LocalizedEnum
{
    const OVERVIEW = 'overview';
    const MEDALS = 'medals';
    const COMPETITIVE = 'competitive';
    const MATCHES = 'matches';
    const CUSTOM = 'custom';
    const LAN = 'lan';
}
