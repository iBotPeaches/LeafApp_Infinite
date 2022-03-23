<?php

namespace App\Console;

use App\Console\Commands\PullHistoricCompetitive;
use App\Console\Commands\PullMetadata;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(PullHistoricCompetitive::class)
            ->withoutOverlapping()
            ->everyThirtyMinutes();

        $schedule->command(PullMetadata::class)
            ->withoutOverlapping()
            ->twiceDaily();
    }
}
