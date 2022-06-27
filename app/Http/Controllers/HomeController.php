<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Analytic;
use App\Models\Medal;
use App\Models\Player;
use App\Support\Analytics\AnalyticInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(): View
    {
        $lastUpdated = Player::query()
            ->with('serviceRecord')
            ->whereHas('serviceRecord', function (Builder $query) {
                $query->where('total_score', '>', 0);
            })
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        $randomMedal = Medal::query()
            ->limit(1)
            ->inRandomOrder()
            ->first();

        $availableAnalytics = Storage::disk('stats')->files();
        $randomAnalytic = 'App\Support\Analytics\Stats\\' .
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
            'medal' => $randomMedal,
            'analyticClass' => $randomAnalyticClass,
            'analytic' => $randomAnalytic
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
