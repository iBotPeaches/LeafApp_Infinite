<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Artesaos\SEOTools\Facades\SEOTools;

class GameController extends Controller
{
    public function index(Game $game): View
    {
        SEOTools::setTitle($game->name);
        SEOTools::addImages([
            $game->category->thumbnail_url,
            $game->map->thumbnail_url,
            $game->playlist->thumbnail_url
        ]);
        SEOTools::setDescription($game->description);

        return view('pages.game', [
            'game' => $game,
        ]);
    }
}
