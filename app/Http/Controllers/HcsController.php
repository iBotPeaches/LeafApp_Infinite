<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Championship;
use Illuminate\Contracts\View\View;

class HcsController extends Controller
{
    public function index(): View
    {
        return view('pages.championships');
    }

    public function championship(Championship $championship): View
    {
        return view('pages.championship', [
            'championship' => $championship
        ]);
    }
}
