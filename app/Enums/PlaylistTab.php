<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OVERVIEW()
 * @method static static STATS()
 */
final class PlaylistTab extends Enum implements LocalizedEnum
{
    const OVERVIEW = 'overview';

    const STATS = 'stats';
}
