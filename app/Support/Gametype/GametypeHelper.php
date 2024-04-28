<?php

declare(strict_types=1);

namespace App\Support\Gametype;

use App\Enums\BaseGametype;
use Illuminate\Support\Str;

class GametypeHelper
{
    public static function findBaseGametype(string $name): BaseGametype
    {
        foreach (BaseGametype::getKeys() as $baseGametype) {
            if (Str::contains($name, Str::replace('_', ' ', $baseGametype), true)) {
                return BaseGametype::fromKey($baseGametype);
            }
        }

        // Special check for KOTH / King of the Hill
        if (Str::contains($name, 'king of the hill', true)) {
            return BaseGametype::KOTH();
        }

        if (Str::contains($name, 'Firefight', true)) {
            return BaseGametype::FIREFIGHT();
        }

        if (Str::contains($name, 'Last Spartan Standing', true)) {
            return BaseGametype::LSS();
        }

        $slayerModes = [
            'Dodgeball',
            'Legendary Fiesta',
            'Heroic Fiesta',
            'Fiesta',
            'Team Snipers',
        ];

        if (Str::contains($name, $slayerModes, true)) {
            return BaseGametype::SLAYER();
        }

        $strongholdModes = [
            'Slayholds',
        ];

        if (Str::contains($name, $strongholdModes, true)) {
            return BaseGametype::STRONGHOLDS();
        }

        throw new \InvalidArgumentException("Unable to find base gametype for: {$name}");
    }
}
