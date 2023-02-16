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
    protected $signature = 'analytics:refresh';

    protected $description = 'Refreshes cache table of top-ten stats.';

    public function handle(): int
    {
        $availableAnalytics = Storage::disk('stats')->files();

        foreach ($availableAnalytics as $availableAnalytic) {
            $analytic = 'App\Support\Analytics\Stats\\'.Str::before($availableAnalytic, '.php');

            /** @var AnalyticInterface $analyticClass */
            $analyticClass = new $analytic();

            ProcessAnalytic::dispatchSync($analyticClass);
        }

        return CommandAlias::SUCCESS;
    }
}
