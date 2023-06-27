<?php

declare(strict_types=1);

namespace App\Support\Schedule;

use App\Console\Kernel;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;

class ScheduleTimer implements ScheduleTimerInterface
{
    public ?Carbon $metadataRefreshDate = null;

    public ?Carbon $topTenRefreshDate = null;

    public ?Carbon $medalRefreshDate = null;

    public ?Carbon $topTenXpDate = null;

    public function __construct()
    {
        // We must load the Console Kernel, as it contains information about our Scheduled Jobs
        // Then we hydrate our Schedule class, which will parse out the cron information.
        app()->make(Kernel::class);
        $schedule = app(Schedule::class);

        collect($schedule->events())->each(function (Event $event) {
            if (Str::contains((string) $event->command, 'app:pull-metadata')) {
                $this->metadataRefreshDate = $event->nextRunDate();
            }

            if (Str::endsWith((string) $event->command, 'analytics:refresh')) {
                $this->topTenRefreshDate = $event->nextRunDate();
            }

            if (Str::contains((string) $event->command, 'analytics:medals:refresh')) {
                $this->medalRefreshDate = $event->nextRunDate();
            }

            if (Str::contains((string) $event->command, 'MostXpPlayer')) {
                $this->topTenXpDate = $event->nextRunDate();
            }
        });
    }
}
