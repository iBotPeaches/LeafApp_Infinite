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
 */
final class Bracket extends Enum implements LocalizedEnum
{
    const WINNERS = 'winners';
    const LOSERS = 'losers';

    public static function coerce($enumKeyOrValue): ?Enum
    {
        $enumKeyOrValue = match ($enumKeyOrValue) {
            1 => self::WINNERS,
            2 => self::LOSERS,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }

    public function toNumerical(): ?int
    {
        return match ((string)$this->value) {
            self::WINNERS => 1,
            self::LOSERS => 2,
            default => null
        };
    }
}
