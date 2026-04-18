<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessAnalytic;
use App\Support\Analytics\AnalyticInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RefreshAnalytics extends Command
{
    protected $signature = 'analytics:refresh {analytic?}';

    protected $description = 'Refreshes cache table of top-ten stats.';

    public function handle(): int
    {
        $availableAnalytics = Storage::disk('stats')->files();
        $analyticOption = $this->argument('analytic');

        if ($analyticOption) {
            $analytic = 'App\Support\Analytics\Stats\\'.$analyticOption;

            if (! is_a($analytic, AnalyticInterface::class, true)) {
                $this->error('Invalid analytic class: '.$analyticOption);

                return self::FAILURE;
            }

            /** @var AnalyticInterface $analyticClass */
            $analyticClass = new $analytic;

            $startTime = time();
            $this->output->writeln('Processing: '.$analyticClass->title());

            ProcessAnalytic::dispatchSync($analyticClass);

            $this->output->writeln('Processed in '.(time() - $startTime).' seconds.');

            return self::SUCCESS;
        }

        foreach ($availableAnalytics as $availableAnalytic) {
            $analytic = 'App\Support\Analytics\Stats\\'.Str::before($availableAnalytic, '.php');

            if (! is_a($analytic, AnalyticInterface::class, true)) {
                continue;
            }

            /** @var AnalyticInterface $analyticClass */
            $analyticClass = new $analytic;

            $startTime = time();
            $this->output->writeln('Processing: '.$analyticClass->title());

            ProcessAnalytic::dispatchSync($analyticClass);

            $this->output->writeln('Processed in '.(time() - $startTime).' seconds.');
        }

        return CommandAlias::SUCCESS;
    }
}
