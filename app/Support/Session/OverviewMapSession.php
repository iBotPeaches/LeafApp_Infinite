<?php

declare(strict_types=1);

namespace App\Support\Session;

use App\Models\Overview;
use Illuminate\Support\Facades\Session;

class OverviewMapSession
{
    private static string $sessionKey = 'overview-map';

    public static function get(Overview $overview): int
    {
        return Session::get(self::getOverviewKey($overview), -1);
    }

    public static function set(Overview $overview, int $type): void
    {
        Session::put(self::getOverviewKey($overview), $type);
    }

    private static function getOverviewKey(Overview $overview): string
    {
        return self::$sessionKey.'-'.$overview->id;
    }
}
