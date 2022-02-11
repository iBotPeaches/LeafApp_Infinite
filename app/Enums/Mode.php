<?php
declare(strict_types=1);

namespace App\Enums;

use App\Services\Autocode\Enums\Filter;
use BenSampo\Enum\Enum;

/**
 * @method static static MATCHMADE_PVP()
 * @method static static MATCHMADE_SOCIAL()
 * @method static static MATCHMADE_RANKED()
 * @method static static MATCHMADE_BOTS()
 * @method static static CUSTOM()
 */
final class Mode extends Enum
{
    const MATCHMADE_PVP = 1;
    const MATCHMADE_SOCIAL = 2;
    const MATCHMADE_RANKED = 3;
    const MATCHMADE_BOTS = 4;
    const CUSTOM = 5;

    public function toAutocodeFilter(): ?Filter
    {
        return match ($this->value) {
            self::MATCHMADE_PVP => Filter::MATCHMADE_PVP(),
            self::MATCHMADE_SOCIAL => Filter::MATCHMADE_SOCIAL(),
            self::MATCHMADE_RANKED => Filter::MATCHMADE_RANKED(),
            self::MATCHMADE_BOTS => Filter::MATCHMADE_BOTS(),
            self::CUSTOM => Filter::CUSTOM(),
            default => null
        };
    }

    public function toPlayerRelation(): string
    {
        return match ($this->value) {
            self::MATCHMADE_PVP => 'serviceRecordPvp',
            default => 'serviceRecord'
        };
    }
}
