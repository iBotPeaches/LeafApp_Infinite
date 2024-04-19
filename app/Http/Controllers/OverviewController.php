<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OverviewTab;
use App\Models\Overview;
use Illuminate\View\View;

class OverviewController extends Controller
{
    public function list(): View
    {
        return view('pages.overviews');
    }

    public function show(Overview $overview, string $tab = OverviewTab::OVERVIEW): View
    {
        return view('pages.overview', [
            'overview' => $overview,
            'tab' => $tab,
        ]);
    }
}
