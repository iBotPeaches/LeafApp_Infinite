<?php

declare(strict_types=1);

namespace App\Support\Session;

use App\Models\Season;
use Illuminate\Support\Facades\Session;

class SeasonSession
{
    private static string $sessionKey = 'season-type';

    public static string $allSeasonKey = '-1';

    public static function get(): string
    {
        return (string) Session::get(self::$sessionKey, self::$allSeasonKey);
    }

    public static function model(): Season
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Season::query()
            ->where('key', self::get())
            ->firstOrNew([
                'key' => self::get(),
            ]);
    }

    public static function set(string $season): void
    {
        Session::put(self::$sessionKey, $season);
    }
}
