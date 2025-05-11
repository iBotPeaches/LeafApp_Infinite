<?php

declare(strict_types=1);

namespace App\Support\Gametype;

use App\Enums\BaseGametype;
use App\Models\Gamevariant;
use Illuminate\Support\Str;

class GametypeHelper
{
    public static function findBaseGametype(Gamevariant $gamevariant): BaseGametype
    {
        $name = $gamevariant->name;

        $genericModes = [
            'Arena',
            'Classic',
        ];

        if (in_array($name, $genericModes, true) && $gamevariant->category) {
            $name = $gamevariant->category->name ?? $name;
        }

        foreach (BaseGametype::getKeys() as $baseGametype) {
            if (Str::contains($name, Str::replace('_', ' ', $baseGametype), true)) {
                return BaseGametype::fromKey($baseGametype);
            }
        }

        $kingOfTheHillModes = [
            'Crazy King',
            'King of the Hill',
        ];

        if (Str::contains($name, $kingOfTheHillModes, true)) {
            return BaseGametype::KOTH();
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
            'Hotswap',
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

        $oddballModes = [
            'Ninja Ball',
            'Speedball',
        ];

        if (Str::contains($name, $oddballModes, true)) {
            return BaseGametype::ODDBALL();
        }

        $juggernautModes = [
            'Ninjanaut',
            'Mutilator',
        ];

        if (Str::contains($name, $juggernautModes, true)) {
            return BaseGametype::JUGGERNAUT();
        }

        $miniGameModes = [
            'Survive The Undead',
            'Sentry Defense',
            'Headhunter',
            'Duck Hunt',
            'Goose Hunt',
            'Hex A Gone',
            '1v1 Showdown',
            'Snowball Fight',
            'Cole Protocol',
        ];

        if (Str::contains($name, $miniGameModes, true)) {
            return BaseGametype::MINI_GAME();
        }

        $infectionGameModes = [
            'Zombies',
            'Maze Craze',
        ];

        if (Str::contains($name, $infectionGameModes, true)) {
            return BaseGametype::INFECTION();
        }

        $ctfModes = [
            'Castle Wars',
        ];

        if (Str::contains($name, $ctfModes, true)) {
            return BaseGametype::CTF();
        }

        if (Str::contains($name, 'Bomb', true)) {
            return BaseGametype::ASSAULT();
        }

        throw new \InvalidArgumentException("Unable to find base gametype for: {$name}");
    }
}
