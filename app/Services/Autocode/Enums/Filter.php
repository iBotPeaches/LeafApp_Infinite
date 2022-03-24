<?php
declare(strict_types=1);

namespace App\Services\Autocode\Enums;

use App\Enums\Mode;
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

    public function toMode(): ?Mode
    {
        return match ((string)$this->value) {
            self::PVP => Mode::MATCHMADE_PVP(),
            default => Mode::MATCHMADE_RANKED()
        };
    }
}
