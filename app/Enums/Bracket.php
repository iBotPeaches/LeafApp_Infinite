<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static WINNERS()
 * @method static static LOSERS()
 * @method static static GRAND()
 */
final class Bracket extends Enum implements LocalizedEnum
{
    const WINNERS = 'winners';
    const LOSERS = 'losers';
    const GRAND = 'grand';

    public static function coerce($enumKeyOrValue): ?Enum
    {
        $enumKeyOrValue = match ($enumKeyOrValue) {
            1 => self::WINNERS,
            2 => self::LOSERS,
            3 => self::GRAND,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }

    public function toNumerical(): ?int
    {
        return match ((string)$this->value) {
            self::WINNERS => 1,
            self::LOSERS => 2,
            self::GRAND => 3,
            default => null
        };
    }
}
