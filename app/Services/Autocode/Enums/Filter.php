<?php
declare(strict_types=1);

namespace App\Services\Autocode\Enums;

use App\Enums\Mode;
use BenSampo\Enum\Enum;

/**
 * @method static static MATCHMADE_PVP()
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

    public function toMode(): ?Mode
    {
        return match ((string)$this->value) {
            self::MATCHMADE_PVP => Mode::MATCHMADE_PVP(),
            self::MATCHMADE_SOCIAL => Mode::MATCHMADE_SOCIAL(),
            self::MATCHMADE_RANKED => Mode::MATCHMADE_RANKED(),
            self::MATCHMADE_BOTS => Mode::MATCHMADE_BOTS(),
            self::CUSTOM => Mode::CUSTOM(),
            default => null
        };
    }
}
