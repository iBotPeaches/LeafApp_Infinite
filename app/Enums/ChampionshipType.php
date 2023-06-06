<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static ROUND_ROBIN()
 * @method static static DOUBLE_ELIM()
 * @method static static STAGE()
 */
final class ChampionshipType extends Enum implements LocalizedEnum
{
    const ROUND_ROBIN = 1;

    const DOUBLE_ELIM = 2;

    const STAGE = 3;

    const BRACKET = 4;

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match ($enumKeyOrValue) {
            'roundRobin' => self::ROUND_ROBIN,
            'doubleElimination' => self::DOUBLE_ELIM,
            'stage' => self::STAGE,
            'bracket' => self::BRACKET,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }

    public function isFfa(): bool
    {
        return $this->is(ChampionshipType::STAGE());
    }

    public function isPoolPlay(): bool
    {
        return $this->is(ChampionshipType::ROUND_ROBIN());
    }
}
