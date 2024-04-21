<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OverviewTab;
use App\Models\Overview;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\View\View;

class OverviewController extends Controller
{
    public function list(): View
    {
        return view('pages.overviews');
    }

    public function show(Overview $overview, string $tab = OverviewTab::OVERVIEW): View
    {
        SEOTools::setTitle('Map Overview: '.$overview->name);
        SEOTools::addImages([
            $overview->image,
        ]);
        SEOTools::setDescription('Halo Infinite - Leaf Map Overview: '.$overview->name);

        return view('pages.overview', [
            'overview' => $overview,
            'tab' => $tab,
        ]);
    }
}
