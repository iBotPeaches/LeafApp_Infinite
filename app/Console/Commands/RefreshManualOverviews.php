<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessOverviewAnalytic;
use App\Models\Overview;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RefreshManualOverviews extends Command
{
    protected $signature = 'analytics:overviews:manual-refresh';

    protected $description = 'Refreshes cache table of overview map stats.';

    public function handle(): int
    {
        /** @var Collection<Overview> $overviews */
        $overviews = Overview::query()
            ->where('is_manual', true)
            ->cursor();

        foreach ($overviews as $overview) {
            $mapIds = $overview->maps->pluck('map_id')->toArray();

            $startTime = time();
            ProcessOverviewAnalytic::dispatchSync($overview->name, $mapIds, true);
            $this->output->writeln('Processed '.$overview->name.' in '.(time() - $startTime).' seconds.');
        }

        return CommandAlias::SUCCESS;
    }
}
