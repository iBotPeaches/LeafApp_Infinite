<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GAME()
 * @method static static PLAYER()
 */
final class AnalyticType extends Enum
{
    const GAME = 'game';
    const PLAYER = 'player';
}
