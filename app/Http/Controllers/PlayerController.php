<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\View\View;

class PlayerController extends Controller
{
    public function index(Player $player): View
    {
        return view('pages.player', [
            'player' => $player
        ]);
    }
}
