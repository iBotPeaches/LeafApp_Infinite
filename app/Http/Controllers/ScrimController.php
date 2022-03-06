<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ScrimTab;
use App\Models\Scrim;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            'type' => $scrimType
        ]);
    }
}
