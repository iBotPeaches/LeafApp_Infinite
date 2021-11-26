<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PlayerTab;
use App\Models\Player;
use Illuminate\View\View;

class PlayerController extends Controller
{
    public function index(Player $player, string $type = PlayerTab::OVERVIEW): View
    {
        return view('pages.player', [
            'player' => $player,
            'type' => $type
        ]);
    }
}
