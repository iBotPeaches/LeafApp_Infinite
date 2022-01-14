<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Medal;
use App\Models\Player;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $lastUpdated = Player::query()
            ->with('serviceRecord')
            ->whereHas('serviceRecord')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        $randomMedal = Medal::query()
            ->limit(1)
            ->inRandomOrder()
            ->first();

        return view('pages.home', [
            'lastUpdated' => $lastUpdated,
            'medal' => $randomMedal
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }
}
