<?php
declare(strict_types=1);

namespace App\Services\Autocode\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static MATCHMADE()
 * @method static static PVP()
 * @method static static RANKED()
 * @method static static SOCIAL()
 * @method static static CUSTOM()
 */
final class Filter extends Enum
{
    const MATCHMADE = 'matchmade';
    const PVP = 'pvp';
    const RANKED = 'ranked';
    const SOCIAL = 'social';
    const CUSTOM = 'custom';
}
