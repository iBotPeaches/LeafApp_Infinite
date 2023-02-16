<?php

declare(strict_types=1);

namespace App\Support\Session;

use App\Enums\Mode;
use Illuminate\Support\Facades\Session;

class ModeSession
{
    private static string $sessionKey = 'player-type';

    public static function get(): Mode
    {
        $mode = Session::get(self::$sessionKey, Mode::MATCHMADE_PVP);

        return Mode::coerce($mode) ?? Mode::MATCHMADE_PVP();
    }

    public static function set(int $type): void
    {
        Session::put(self::$sessionKey, $type);
    }
}
