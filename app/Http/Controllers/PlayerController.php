<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PlayerTab;
use App\Models\Player;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlayerController extends Controller
{
    public function index(Player $player, string $type = PlayerTab::OVERVIEW): View
    {
        SEOTools::setTitle($player->gamertag . ' ' . Str::title($type));
        SEOTools::addImages([
            $player->emblem_url
        ]);
        SEOTools::setDescription($player->gamertag . ' Halo Infinite ' . Str::title($type));

        return view('pages.player', [
            'player' => $player,
            'type' => $type
        ]);
    }
}
