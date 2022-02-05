<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static HCS()
 * @method static static APPEARANCE()
 * @method static static MATCH_HISTORY()
 * @method static static XUID()
 */
final class QueueName extends Enum
{
    const HCS = 'hcs';
    const APPEARANCE = 'appearance';
    const MATCH_HISTORY = 'match_history';
    const XUID = 'xuid';
}
