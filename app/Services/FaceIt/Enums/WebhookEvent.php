<?php

declare(strict_types=1);

namespace App\Services\FaceIt\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static MATCH_STATUS_FINISHED()
 * @method static static CHAMPIONSHIP_FINISHED()
 */
final class WebhookEvent extends Enum implements LocalizedEnum
{
    const MATCH_STATUS_FINISHED = 'match_status_finished';

    const CHAMPIONSHIP_FINISHED = 'championship_finished';
}
