<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ScrimTab;
use App\Jobs\ExportScrimPlayers;
use App\Models\Scrim;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScrimController extends Controller
{
    public function index(): View
    {
        return view('pages.scrims');
    }

    public function show(Request $request, Scrim $scrim, string $scrimType = ScrimTab::OVERVIEW): View
    {
        return view('pages.scrim', [
            'scrim' => $scrim,
            'user' => $request->user(),
            'type' => $scrimType,
        ]);
    }

    public function csvPlayers(Request $request, Scrim $scrim): StreamedResponse
    {
        /** @var array $data */
        $data = ExportScrimPlayers::dispatchSync($scrim);
        $writer = Writer::createFromString();
        $writer->insertOne(ExportScrimPlayers::$header);
        $writer->insertAll($data);

        $title = Str::slug('Scrim-'.$scrim->id.'-Infinite-').'.csv';

        return response()->streamDownload(function () use ($writer) {
            echo $writer->toString();
        }, $title, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'X-Robots-Tag' => 'noindex',
        ]);
    }
}
