<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Bracket;
use App\Models\Championship;
use App\Models\Matchup;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;

class HcsController extends Controller
{
    public function index(): View
    {
        return view('pages.championships');
    }

    public function championship(Championship $championship, string $bracket = Bracket::WINNERS, int $round = 1): View
    {
        SEOTools::setTitle($championship->name);
        SEOTools::setDescription($championship->name . ' (' . $championship->region  . ')');

        return view('pages.championship', [
            'championship' => $championship,
            'bracket' => $bracket,
            'round' => $round
        ]);
    }

    public function matchup(Championship $championship, Matchup $matchup): View
    {
        $matchup->load('matchupTeams.players');

        SEOTools::setTitle($matchup->title);
        SEOTools::setDescription($matchup->description);

        return view('pages.matchup', [
            'championship' => $championship,
            'matchup' => $matchup
        ]);
    }
}
