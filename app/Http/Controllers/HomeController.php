<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Mode;
use App\Models\Analytic;
use App\Models\MedalAnalytic;
use App\Models\Player;
use App\Support\Analytics\AnalyticInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(): View
    {
        $lastUpdated = Player::query()
            ->with('serviceRecord')
            ->whereNotNull('emblem_url')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        $medalAnalytic = MedalAnalytic::query()
            ->limit(1)
            ->inRandomOrder()
            ->where('mode', Mode::MATCHMADE_PVP)
            ->whereNull('season_id')
            ->where('place', 1)
            ->first();

        $availableAnalytics = Storage::disk('stats')->files();
        $randomAnalytic = 'App\Support\Analytics\Stats\\'.
            Str::before($availableAnalytics[array_rand($availableAnalytics)], '.php');

        /** @var AnalyticInterface $randomAnalyticClass */
        $randomAnalyticClass = new $randomAnalytic();

        $randomAnalytic = Analytic::query()
            ->limit(1)
            ->where('key', $randomAnalyticClass->key())
            ->orderByDesc('value')
            ->first();

        return view('pages.home', [
            'lastUpdated' => $lastUpdated,
            'medalAnalytic' => $medalAnalytic,
            'analyticClass' => $randomAnalyticClass,
            'analytic' => $randomAnalytic,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function legal(): View
    {
        return view('pages.legal');
    }
}
