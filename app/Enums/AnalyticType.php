<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GAME()
 * @method static static PLAYER()
 * @method static static ONLY_GAME()
 * @method static static MAP()
 * @method static static ONLY_PLAYER()
 */
final class AnalyticType extends Enum
{
    const GAME = 'game';

    const PLAYER = 'player';

    const ONLY_GAME = 'only_game';

    const MAP = 'map';

    const ONLY_PLAYER = 'only_player';

    public function isGame(): bool
    {
        return $this->is(self::GAME()) || $this->is(self::ONLY_GAME());
    }

    public function isMap(): bool
    {
        return $this->is(self::MAP());
    }
}
