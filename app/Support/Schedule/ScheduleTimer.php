<?php

declare(strict_types=1);

namespace App\Support\Schedule;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;

class ScheduleTimer implements ScheduleTimerInterface
{
    public ?Carbon $metadataRefreshDate = null;
    public ?Carbon $topTenRefreshDate = null;
    public ?Carbon $medalRefreshDate = null;

    public function __construct()
    {
        $schedule = app(Schedule::class);

        collect($schedule->events())->each(function (Event $event) {
            if (Str::contains($event->command, 'app:pull-metadata')) {
                $this->metadataRefreshDate = $event->nextRunDate();
            }

            if (Str::contains($event->command, 'analytics:refresh')) {
                $this->topTenRefreshDate = $event->nextRunDate();
            }

            if (Str::contains($event->command, 'analytics:medal:refresh')) {
                $this->medalRefreshDate = $event->nextRunDate();
            }
        });
    }
}
