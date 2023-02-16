<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\ExportGame;
use App\Models\Game;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Str;
use Illuminate\View\View;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GameController extends Controller
{
    public function index(Game $game): View
    {
        $images = [
            $game->category->thumbnail_url,
            $game->map->thumbnail_url,
        ];

        if ($game->playlist) {
            $images[] = $game->playlist->thumbnail_url;
        }

        SEOTools::setTitle($game->name);
        SEOTools::addImages($images);
        SEOTools::setDescription($game->description);

        return view('pages.game', [
            'game' => $game,
        ]);
    }

    public function csv(Game $game): StreamedResponse
    {
        /** @var array $data */
        $data = ExportGame::dispatchSync($game);

        $writer = Writer::createFromString();
        $writer->insertOne(ExportGame::$header);
        $writer->insertAll($data);

        $title = Str::slug($game->name.'-'.$game->occurred_at->toCookieString().'-export').'.csv';

        return response()->streamDownload(function () use ($writer) {
            echo $writer->toString();
        }, $title, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
