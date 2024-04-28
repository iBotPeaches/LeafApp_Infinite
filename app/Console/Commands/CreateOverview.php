<?php

namespace App\Console\Commands;

use App\Jobs\ProcessOverviewAnalytic;
use App\Models\Game;
use App\Models\Map;
use Illuminate\Console\Command;

class CreateOverview extends Command
{
    protected $signature = 'app:create-overview';

    protected $description = 'Create an Overview based on map uuid.';

    public function handle(): int
    {
        $mapUuid = $this->ask('What is the map uuid?');

        $map = Map::query()
            ->where('uuid', $mapUuid)
            ->first();

        if ($map === null) {
            /** @var Game|null $game */
            $game = Game::query()
                ->where('uuid', $mapUuid)
                ->first();

            $map = $game?->map;
        }

        if ($map === null) {
            $this->error('Map/Game not found.');

            return self::FAILURE;
        }

        $startTime = time();
        ProcessOverviewAnalytic::dispatchSync($map->name, [$map->id], true);
        $this->output->writeln('Processed '.$map->name.' in '.(time() - $startTime).' seconds.');

        return self::SUCCESS;
    }
}
