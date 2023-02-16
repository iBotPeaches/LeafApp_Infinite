<?php

declare(strict_types=1);

namespace App\Services\Autocode\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PLAYER()
 * @method static static BOT()
 */
final class PlayerType extends Enum
{
    const PLAYER = 'player';

    const BOT = 'bot';
}
