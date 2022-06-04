<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Medal;
use App\Models\Player;
use App\Support\Analytics\Stats\MostKillsServiceRecord;
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
            ->limit(9)
            ->get();

        $randomMedal = Medal::query()
            ->limit(1)
            ->inRandomOrder()
            ->first();

        $availableAnalytics = Storage::disk('stats')->files();
        $randomAnalytic = 'App\Support\Analytics\Stats\\' .
           Str::before($availableAnalytics[array_rand($availableAnalytics)], '.php');

        return view('pages.home', [
            'lastUpdated' => $lastUpdated,
            'medal' => $randomMedal,
            'analytic' => new $randomAnalytic()
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
