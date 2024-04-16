<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessOverviewAnalytic;
use App\Models\Game;
use App\Models\Map;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RefreshOverviews extends Command
{
    protected $signature = 'analytics:overviews:refresh';

    protected $description = 'Refreshes cache table of overview map stats.';

    public function handle(): int
    {
        $playlistMapIds = Game::query()
            ->select('map_id')
            ->whereNotNull('playlist_id');

        $maps = Map::query()
            ->whereIn('id', $playlistMapIds)
            ->whereNotNull('level_id')
            ->orderBy('name')
            ->cursor();

        $mapIdsByName = [];

        $maps->each(function (Map $map) use (&$mapIdsByName) {
            $mapIdsByName[$map->shorthand][] = $map->id;
        });

        foreach ($mapIdsByName as $name => $mapIds) {
            ProcessOverviewAnalytic::dispatchSync($name, $mapIds);
        }

        return CommandAlias::SUCCESS;
    }
}
