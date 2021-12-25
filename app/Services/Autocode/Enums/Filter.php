<?php
declare(strict_types=1);

namespace App\Services\Autocode\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static MATCHMADE_PHP()
 * @method static static MATCHMADE_SOCIAL()
 * @method static static MATCHMADE_RANKED()
 * @method static static MATCHMADE_BOTS()
 * @method static static CUSTOM()
 */
final class Filter extends Enum
{
    const MATCHMADE_PVP = 'matchmade:pvp';
    const MATCHMADE_SOCIAL = 'matchmade:social';
    const MATCHMADE_RANKED = 'matchmade:ranked';
    const MATCHMADE_BOTS = 'matchmade:bots';
    const CUSTOM = 'custom';
}
