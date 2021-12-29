<?php
declare(strict_types=1);

namespace App\Services\Autocode\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static MATCHMADE()
 * @method static static CUSTOM()
 */
final class Mode extends Enum
{
    const MATCHMADE = 'matchmade';
    const CUSTOM = 'custom';
}
