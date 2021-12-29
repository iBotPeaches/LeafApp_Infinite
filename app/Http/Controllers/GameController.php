<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(Game $game): View
    {
        return view('pages.game', [
            'game' => $game,
        ]);
    }
}
