<?php
declare(strict_types=1);

namespace App\Services\Autocode\Enums;

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

    public function getLastGameIdVariable(): string
    {
        return match ($this->value) {
            self::MATCHMADE => 'last_game_id_pulled',
            self::CUSTOM => 'last_custom_game_id_pulled',
            self::LAN => 'last_lan_game_id_pulled'
        };
    }
}
