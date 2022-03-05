<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ScrimTab;
use App\Models\Scrim;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScrimController extends Controller
{
    public function index(Request $request, Scrim $scrim, string $type = ScrimTab::OVERVIEW): View
    {
        return view('pages.scrim', [
            'scrim' => $scrim,
            'user' => $request->user(),
            'type' => $type
        ]);
    }
}
