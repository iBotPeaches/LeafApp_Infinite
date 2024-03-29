<?php

declare(strict_types=1);

namespace App\Services\FaceIt\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static MATCH_STATUS_FINISHED()
 * @method static static MATCH_OBJECT_CREATED()
 * @method static static CHAMPIONSHIP_CREATED()
 * @method static static CHAMPIONSHIP_CANCELLED()
 * @method static static CHAMPIONSHIP_FINISHED()
 * @method static static CHAMPIONSHIP_STARTED()
 */
final class WebhookEvent extends Enum implements LocalizedEnum
{
    const MATCH_STATUS_FINISHED = 'match_status_finished';

    const MATCH_OBJECT_CREATED = 'match_object_created';

    const CHAMPIONSHIP_CREATED = 'championship_created';

    const CHAMPIONSHIP_CANCELLED = 'championship_cancelled';

    const CHAMPIONSHIP_FINISHED = 'championship_finished';

    const CHAMPIONSHIP_STARTED = 'championship_started';
}
