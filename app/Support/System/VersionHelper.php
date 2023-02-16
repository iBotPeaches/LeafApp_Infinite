<?php

declare(strict_types=1);

namespace App\Support\System;

use Illuminate\Support\Facades\File;

class VersionHelper
{
    public static function getVersionString(): ?string
    {
        $versionFile = base_path('VERSION');

        if (File::exists($versionFile)) {
            return File::get($versionFile);
        }

        return null;
    }
}
