<?php

namespace App\Jobs;

use App\Enums\BaseGametype;
use App\Enums\QueueName;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Gamevariant;
use App\Models\Map;
use App\Models\Overview;
use App\Support\Gametype\GametypeHelper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProcessOverviewAnalytic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $mapName,
        public array $mapIds,
        public bool $isManual = false,
    ) {
        $this->onQueue(QueueName::RECORDS);
    }

    public function handle(): void
    {
        $mapSlug = Str::slug($this->mapName);

        // HACK - Obtain image URL
        /** @var Map|null $map */
        $map = Map::query()
            ->whereIn('id', $this->mapIds)
            ->first();

        /** @var Overview $overview */
        $overview = Overview::query()
            ->firstOrNew([
                'slug' => $mapSlug,
                'is_manual' => $this->isManual,
            ], [
                'name' => $this->mapName,
                'is_manual' => $this->isManual,
            ]);

        // If the overview was updated within the past month (and non-manual), skip processing
        if (! $this->isManual && $overview->exists && $overview->updated_at->diffInDays(absolute: true) < 31) {
            return;
        }

        $overview->name = $this->mapName;
        $overview->thumbnail_url = $map->thumbnail_url ?? '';
        $overview->touch();
        $overview->save();

        $this->parseOverviewMaps($overview);
        $this->parseOverviewGametypes($overview);
        $this->parseOverviewStats($overview);
    }

    private function parseOverviewMaps(Overview $overview): void
    {
        foreach ($this->mapIds as $mapId) {
            $releasedAt = Game::query()
                ->select('occurred_at')
                ->where('map_id', $mapId)
                ->orderByDesc('occurred_at')
                ->value('occurred_at');

            $overview->maps()->updateOrCreate([
                'map_id' => $mapId,
            ], [
                'released_at' => Carbon::parse($releasedAt)->setTime(0, 0),
            ]);
        }
    }

    private function parseOverviewGametypes(Overview $overview): void
    {
        $playlistMethod = $overview->is_manual ? 'whereNull' : 'whereNotNull';
        $gametypeIds = Game::query()
            ->select('gamevariant_id')
            ->whereIn('map_id', $this->mapIds)
            ->$playlistMethod('playlist_id')
            ->distinct()
            ->pluck('gamevariant_id');

        $variantMapping = [];
        Gamevariant::query()
            ->with('category')
            ->whereIn('id', $gametypeIds)
            ->each(function (Gamevariant $gamevariant) use (&$variantMapping) {
                $baseMode = GametypeHelper::findBaseGametype($gamevariant);
                $variantMapping[$baseMode->value][] = $gamevariant->id;
            });

        foreach ($variantMapping as $baseMode => $variantIds) {
            $overview->gametypes()->updateOrCreate([
                'gametype' => $baseMode,
            ], [
                'name' => BaseGametype::fromValue($baseMode)->description,
                'gamevariant_ids' => $variantIds,
            ]);
        }
    }

    private function parseOverviewStats(Overview $overview): void
    {
        $playlistMethod = $overview->is_manual ? 'whereNull' : 'whereNotNull';
        $mapIds = $overview->maps->pluck('map_id');

        $gameBuilder = Game::query()
            ->select('id')
            ->whereIn('map_id', $mapIds)
            ->$playlistMethod('playlist_id');

        $playerBuilder = GamePlayer::query()
            ->whereIn('game_id', $gameBuilder);

        // Overall
        $this->produceOverviewStats($overview, $gameBuilder, $playerBuilder);

        // Per Gametype
        foreach ($overview->gametypes as $gametype) {
            $gameBuilder = Game::query()
                ->select('id')
                ->whereIn('map_id', $mapIds)
                ->whereIn('gamevariant_id', $gametype->gamevariant_ids)
                ->$playlistMethod('playlist_id');

            $playerBuilder = GamePlayer::query()
                ->whereIn('game_id', $gameBuilder);

            $this->produceOverviewStats($overview, $gameBuilder, $playerBuilder, null, $gametype->id);
        }

        // Per Map
        foreach ($overview->maps as $map) {
            $gameBuilder = Game::query()
                ->select('id')
                ->where('map_id', $map->map_id)
                ->$playlistMethod('playlist_id');

            $playerBuilder = GamePlayer::query()
                ->whereIn('game_id', $gameBuilder);

            $this->produceOverviewStats($overview, $gameBuilder, $playerBuilder, $map->id);

            // Per Gametype & Map
            foreach ($overview->gametypes as $gametype) {
                $gameBuilder = Game::query()
                    ->select('id')
                    ->where('map_id', $map->map_id)
                    ->whereIn('gamevariant_id', $gametype->gamevariant_ids)
                    ->$playlistMethod('playlist_id');

                $playerBuilder = GamePlayer::query()
                    ->whereIn('game_id', $gameBuilder);

                $this->produceOverviewStats($overview, $gameBuilder, $playerBuilder, $map->id, $gametype->id);
            }
        }
    }

    private function produceOverviewStats(Overview $overview,
        Builder $gameBuilder,
        Builder $playerBuilder,
        ?int $overviewMapId = null,
        ?int $overviewGametypeId = null): void
    {
        $totalMatches = $gameBuilder
            ->clone()
            ->count();

        if ($totalMatches === 0) {
            return;
        }

        $totalSecondsPlayed = $gameBuilder
            ->clone()
            ->sum('duration_seconds');

        $totalDnf = $playerBuilder
            ->clone()
            ->where('was_at_end', false)
            ->count();

        $query = <<<'SQL'
            COUNT(player_id) as total_players,
            COUNT(distinct player_id) as total_distinct_players,
            SUM(kills) as total_kills,
            SUM(deaths) as total_deaths,
            SUM(assists) as total_assists,
            SUM(suicides) as total_suicides,
            SUM(medal_count) as total_medals,
            AVG(kd) as average_kd,
            AVG(kda) as average_kda,
            AVG(accuracy) as average_accuracy
        SQL;

        /** @var GamePlayer $stats */
        $stats = $playerBuilder
            ->clone()
            ->selectRaw($query)
            ->first();

        $totalPlayers = Arr::get($stats, 'total_players', 0);
        $totalDistinctPlayers = Arr::get($stats, 'total_distinct_players', 0);
        $totalKills = Arr::get($stats, 'total_kills', 0);
        $totalDeaths = Arr::get($stats, 'total_deaths', 0);
        $totalAssists = Arr::get($stats, 'total_assists', 0);
        $totalSuicides = Arr::get($stats, 'total_suicides', 0);
        $totalMedals = Arr::get($stats, 'total_medals', 0);
        $averageKd = Arr::get($stats, 'average_kd', 0);
        $averageKda = Arr::get($stats, 'average_kda', 0);
        $averageAccuracy = Arr::get($stats, 'average_accuracy', 0);

        $overview->stats()->updateOrCreate([
            'overview_gametype_id' => $overviewGametypeId,
            'overview_map_id' => $overviewMapId,
        ], [
            'total_matches' => $totalMatches,
            'total_seconds_played' => $totalSecondsPlayed,
            'total_players' => $totalPlayers,
            'total_unique_players' => $totalDistinctPlayers,
            'total_dnf' => $totalDnf,
            'total_kills' => $totalKills,
            'total_deaths' => $totalDeaths,
            'total_assists' => $totalAssists,
            'total_suicides' => $totalSuicides,
            'total_medals' => $totalMedals,
            'average_kd' => $averageKd,
            'average_kda' => $averageKda,
            'average_accuracy' => $averageAccuracy,
        ]);
    }
}
