<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Medal;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function medal(Medal $medal): View
    {
        SEOTools::setTitle($medal->name . ' Leaderboards');
        SEOTools::addImages([
            $medal->image
        ]);
        SEOTools::setDescription('Halo Infinite Medal: ' . $medal->name . ' Leaderboards');

        return view('pages.medal-leaderboard', [
            'medal' => $medal
        ]);
    }
}
