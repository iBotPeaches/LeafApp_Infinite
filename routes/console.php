<?php

use App\Console\Commands\PullMetadata;
use App\Console\Commands\RefreshAnalytics;
use App\Console\Commands\RefreshMedals;
use Illuminate\Support\Facades\Schedule;
use Laravel\Horizon\Console\SnapshotCommand;

Schedule::call(PullMetadata::class)
    ->name('pull-metadata')
    ->withoutOverlapping()
    ->twiceDaily();

Schedule::call(RefreshAnalytics::class)
    ->name('refresh-analytics')
    ->withoutOverlapping()
    ->dailyAt('12:01')
    ->timezone('America/New_York');

Schedule::call(RefreshMedals::class)
    ->name('refresh-medals')
    ->withoutOverlapping()
    ->dailyAt('3:01')
    ->timezone('America/New_York');

Schedule::call(SnapshotCommand::class)
    ->everyFiveMinutes();
