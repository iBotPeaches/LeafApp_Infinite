<?php
declare(strict_types=1);

namespace App\Support\Schedule;

use Illuminate\Console\Scheduling\Schedule;

interface ScheduleTimerInterface
{
    public function __construct();
}
