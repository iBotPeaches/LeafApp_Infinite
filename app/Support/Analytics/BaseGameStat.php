<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\GamePlayer;
use Illuminate\Database\Eloquent\Builder;

class BaseGameStat
{
    public function type(): AnalyticType
    {
        return AnalyticType::GAME();
    }

    public function builder(): Builder
    {
        return GamePlayer::query();
    }

    public function getPlaylistsToIgnore(): array
    {
        return [
            config('services.halo.playlists.bot-bootcamp'),
            config('services.halo.playlists.survive-the-dead'),
            config('services.halo.playlists.firefight-koth'),
            config('services.halo.playlists.firefight-heroic'),
            config('services.halo.playlists.firefight-legendary'),
            config('services.halo.playlists.firefight-grunt-koth'),
            config('services.halo.playlists.firefight-grunt-heroic'),
            config('services.halo.playlists.firefight-grunt-legendary'),
            config('services.halo.playlists.firefight-composer-normal'),
            config('services.halo.playlists.firefight-composer-heroic'),
            config('services.halo.playlists.firefight-composer-legendary'),
            config('services.halo.playlists.firefight-battle-for-reach'),
            config('services.halo.playlists.firefight-3person'),
            config('services.halo.playlists.firefight-fiesta'),
            config('services.halo.playlists.firefight-classic'),
        ];
    }
}
