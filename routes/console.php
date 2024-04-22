<?php

use App\Console\Commands\CheckForUnban;
use App\Console\Commands\PullMetadata;
use App\Console\Commands\RefreshAnalytics;
use App\Console\Commands\RefreshMedals;
use App\Console\Commands\RefreshOverviews;
use Illuminate\Support\Facades\Schedule;
use Laravel\Horizon\Console\SnapshotCommand;

Schedule::command(PullMetadata::class)
    ->withoutOverlapping()
    ->twiceDaily();

Schedule::command(RefreshAnalytics::class)
    ->withoutOverlapping()
    ->dailyAt('12:01')
    ->timezone('America/New_York');

Schedule::command(RefreshMedals::class)
    ->withoutOverlapping()
    ->dailyAt('3:01')
    ->timezone('America/New_York');

Schedule::command(RefreshOverviews::class)
    ->withoutOverlapping()
    ->monthly()
    ->timezone('America/New_York');

Schedule::command(SnapshotCommand::class)
    ->everyFiveMinutes();

Schedule::command(CheckForUnban::class)
    ->daily()
    ->withoutOverlapping();
