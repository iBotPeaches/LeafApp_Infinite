<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OVERVIEW()
 * @method static static MATCHES()
 * @method static static PLAYERS()
 */
final class ScrimTab extends Enum implements LocalizedEnum
{
    const OVERVIEW = 'overview';

    const MATCHES = 'matches';

    const PLAYERS = 'players';
}
