<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Models\Medal;
use App\Support\Schedule\ScheduleTimer;
use App\Support\Schedule\ScheduleTimerInterface;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function medalList(): View
    {
        return view('pages.medal-leaderboards');
    }

    public function topTenList(): View
    {
        return view('pages.topten-leaderboards');
    }

    public function medal(Medal $medal): View
    {
        SEOTools::setTitle($medal->name.' Leaderboards');
        SEOTools::addImages([
            $medal->image,
        ]);
        SEOTools::setDescription('Halo Infinite Medal: '.$medal->name.' Leaderboards');

        /** @var ScheduleTimer $timer */
        $timer = resolve(ScheduleTimerInterface::class);

        return view('pages.medal-leaderboard', [
            'medal' => $medal,
            'nextDate' => $timer->medalRefreshDate,
        ]);
    }

    public function topTen(AnalyticKey $key): View
    {
        $analyticClass = Analytic::getStatFromEnum($key);

        SEOTools::setTitle($analyticClass->title().' Top Ten Leaderboards');
        SEOTools::setDescription('Top Ten Halo Infinite Leaderboards: '.$analyticClass->title());

        return view('pages.topten-leaderboard', [
            'analyticClass' => $analyticClass,
        ]);
    }
}
