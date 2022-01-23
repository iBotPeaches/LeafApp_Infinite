<?php
declare(strict_types=1);

namespace App\Services\FaceIt\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NA()
 * @method static static EU()
 */
final class Region extends Enum
{
    const NA = 1;
    const EU = 2;
}
