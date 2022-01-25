<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @method static static WINNERS()
 * @method static static LOSERS()
 * @method static static GRAND_FINALS()
 */
final class Bracket extends Enum implements LocalizedEnum
{
    const WINNERS = 'winners';
    const LOSERS = 'losers';
    const GRAND_FINALS = 'finals';

    public static function coerce($enumKeyOrValue): ?Enum
    {
        $enumKeyOrValue = match ($enumKeyOrValue) {
            1 => self::WINNERS,
            2 => self::LOSERS,
            3 => self::GRAND_FINALS,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }

    public function toNumerical(): int
    {
        return match ($this->value) {
            self::WINNERS => 1,
            self::LOSERS => 2,
            self::GRAND_FINALS => 3,
        };
    }
}
