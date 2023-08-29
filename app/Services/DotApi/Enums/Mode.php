<?php

declare(strict_types=1);

namespace App\Services\DotApi\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static MATCHMADE()
 * @method static static CUSTOM()
 * @method static static LAN()
 */
final class Mode extends Enum
{
    const MATCHMADE = 'matchmaking';

    const CUSTOM = 'custom';

    const LAN = 'local';

    public static function coerce(mixed $enumKeyOrValue): ?static
    {
        $enumKeyOrValue = match (strtolower($enumKeyOrValue)) {
            'matchmade' => self::MATCHMADE,
            default => $enumKeyOrValue
        };

        return parent::coerce($enumKeyOrValue);
    }

    public function getLastGameIdVariable(): string
    {
        return match ($this->value) {
            self::CUSTOM => 'last_custom_game_id_pulled',
            self::LAN => 'last_lan_game_id_pulled',
            default => 'last_game_id_pulled',
        };
    }
}
