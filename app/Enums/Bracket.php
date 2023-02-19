<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static WINNERS()
 * @method static static LOSERS()
 * @method static static GRAND()
 * @method static static OTHER()
 * @method static static POOL_A()
 * @method static static POOL_B()
 * @method static static POOL_C()
 * @method static static POOL_D()
 * @method static static RULES()
 */
final class Bracket extends Enum implements LocalizedEnum
{
    const WINNERS = 'winners';

    const LOSERS = 'losers';

    const GRAND = 'grand';

    const OTHER = 'other';

    const POOL_A = 'a';

    const POOL_B = 'b';

    const POOL_C = 'c';

    const POOL_D = 'd';

    const RULES = 'rules';

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match ($enumKeyOrValue) {
            1, self::POOL_A => self::WINNERS,
            2, self::POOL_B => self::LOSERS,
            3, self::POOL_C => self::GRAND,
            4, self::POOL_D => self::OTHER,
            5, self::RULES => self::RULES,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }

    public function toNumerical(): ?int
    {
        return match ((string) $this->value) {
            self::WINNERS => 1,
            self::LOSERS => 2,
            self::GRAND => 3,
            self::OTHER => 4,
            self::RULES => 5,
            default => null
        };
    }
}
