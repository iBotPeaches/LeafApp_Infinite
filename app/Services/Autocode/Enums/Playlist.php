<?php
declare(strict_types=1);

namespace App\Services\Autocode\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static RANKED_ALL()
 * @method static static RANKED_OPEN_CROSSPLAY()
 * @method static static RANKED_SOLO_DUO_CONTROLLER()
 * @method static static RANKED_SOLO_DUO_MNK()
 */
final class Playlist extends Enum
{
    const RANKED_ALL = 'ranked:all';
    const RANKED_OPEN_CROSSPLAY = 'ranked:open:crossplay';
    const RANKED_SOLO_DUO_CONTROLLER = 'ranked:solo-duo:controller';
    const RANKED_SOLO_DUO_MNK = 'ranked:solo-duo:mnk';
}
