<?php

declare(strict_types=1);

namespace App\Services\HaloDotApi\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PLAYER()
 * @method static static BOT()
 */
final class PlayerType extends Enum
{
    const PLAYER = 'human';

    const BOT = 'bot';
}
