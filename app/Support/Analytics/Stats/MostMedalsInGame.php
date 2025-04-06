<?php

declare(strict_types=1);

namespace App\Support\Analytics\Stats;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Models\PlaylistAnalytic;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\BaseGameStat;
use App\Support\Analytics\Traits\HasExportUrlGeneration;
use App\Support\Analytics\Traits\HasGamePlayerExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MostMedalsInGame extends BaseGameStat implements AnalyticInterface
{
    use HasExportUrlGeneration;
    use HasGamePlayerExport;

    public function title(): string
    {
        return 'Most Medals in Game';
    }

    public function key(): string
    {
        return AnalyticKey::MOST_MEDALS_GAME->value;
    }

    public function unit(): string
    {
        return 'medals';
    }

    public function property(): string
    {
        return 'medal_count';
    }

    public function displayProperty(Analytic|PlaylistAnalytic $analytic): string
    {
        return number_format($analytic->value);
    }

    public function resultBuilder(): Builder
    {
        return $this->baseBuilder()
            ->select('game_players.*')
            ->with(['game', 'player'])
            ->leftJoin('players', 'players.id', '=', 'game_players.player_id')
            ->leftJoin('games', 'game_players.game_id', '=', 'games.id')
            ->leftJoin('playlists', 'games.playlist_id', '=', 'playlists.id')
            ->whereNotIn('playlists.uuid', $this->getPlaylistsToIgnore())
            ->where('players.is_cheater', false)
            ->where('players.is_bot', false)
            ->where('players.is_botfarmer', false)
            ->whereNotNull('games.playlist_id')
            ->orderByDesc($this->property());
    }

    public function results(int $limit = 10): ?Collection
    {
        return $this->resultBuilder()
            ->limit($limit)
            ->get();
    }
}
