<?php
declare(strict_types=1);

namespace App\Support\Session;

use Illuminate\Support\Facades\Session;

class SeasonSession
{
    private static string $sessionKey = 'season-type';

    public static function get(): int
    {
        return (int)Session::get(self::$sessionKey, config('services.autocode.competitive.season'));
    }

    public static function set(int $season): void
    {
        Session::put(self::$sessionKey, $season);
    }
}
