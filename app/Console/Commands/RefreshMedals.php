<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessMedalAnalytic;
use App\Models\Medal;
use App\Models\Season;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RefreshMedals extends Command
{
    protected $signature = 'analytics:medals:refresh {--all}';

    protected $description = 'Refreshes cache table of medal stats.';

    public function handle(): int
    {
        /** @var Season[]|Collection $seasons */
        $seasons = Season::all();

        /** @var Medal[]|Collection $medals */
        $medals = Medal::all();

        // ALL
        $medals->each(function (Medal $medal) {
            ProcessMedalAnalytic::dispatchSync($medal);
        });

        // Seasons
        if ($this->option('all')) {
            $seasons->each(function (Season $season) use ($medals) {
                $medals->each(function (Medal $medal) use ($season) {
                    ProcessMedalAnalytic::dispatchSync($medal, $season);
                });
            });
        }

        return CommandAlias::SUCCESS;
    }
}
