<?php

declare(strict_types=1);

namespace App\Actions\Map;

use Illuminate\Support\Str;

class StandardizeMapName
{
    public static function execute(string $mapName): string
    {
        $suffixesToTrim = [
            ' - Ranked',
            ' - Husky Raid',
        ];

        return Str::trim(Str::remove($suffixesToTrim, $mapName));
    }
}
