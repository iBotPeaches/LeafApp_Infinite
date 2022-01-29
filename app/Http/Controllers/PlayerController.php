<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PlayerTab;
use App\Jobs\ExportGameHistory;
use App\Models\Player;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Str;
use Illuminate\View\View;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function csv(Player $player): StreamedResponse
    {
        /** @var array $data */
        $data = ExportGameHistory::dispatchSync($player);

        $writer = Writer::createFromString();
        $writer->insertOne(ExportGameHistory::$header);
        $writer->insertAll($data);

        $title = Str::slug($player->gamertag . '-InfiniteMatchHistory') . '.csv';

        return response()->streamDownload(function () use ($writer) {
            echo $writer->toString();
        }, $title, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
