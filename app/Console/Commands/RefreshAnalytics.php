<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessAnalytic;
use App\Models\Season;
use App\Support\Analytics\AnalyticInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RefreshAnalytics extends Command
{
    protected $signature = 'analytics:refresh';

    protected $description = 'Refreshes cache table of top-ten stats.';

    public function handle(): int
    {
        $availableAnalytics = Storage::disk('stats')->files();
        $seasons = Season::all();

        // ALL
        foreach ($availableAnalytics as $availableAnalytic) {
            $analytic = 'App\Support\Analytics\Stats\\'.Str::before($availableAnalytic, '.php');

            /** @var AnalyticInterface $analyticClass */
            $analyticClass = new $analytic();

            $startTime = time();
            $this->output->writeln('Processing: '.$analyticClass->title());

            ProcessAnalytic::dispatchSync($analyticClass);

            $this->output->writeln('Processed in '.(time() - $startTime).' seconds.');
        }

        // Seasons
        $seasons->each(function (Season $season) use ($availableAnalytics) {
            foreach ($availableAnalytics as $availableAnalytic) {
                $analytic = 'App\Support\Analytics\Stats\\'.Str::before($availableAnalytic, '.php');

                /** @var AnalyticInterface $analyticClass */
                $analyticClass = new $analytic($season);

                $startTime = time();
                $this->output->writeln('Processing: '.$analyticClass->title());

                ProcessAnalytic::dispatchSync($analyticClass);

                $this->output->writeln('Processed in '.(time() - $startTime).' seconds.');
            }
        });

        return CommandAlias::SUCCESS;
    }
}
