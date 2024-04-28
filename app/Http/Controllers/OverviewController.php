<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OverviewTab;
use App\Enums\OverviewType;
use App\Models\Overview;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\View\View;

class OverviewController extends Controller
{
    public function list(string $filterType = OverviewType::MATCHMAKING): View
    {
        SEOTools::setTitle('Map Overviews');
        SEOTools::setDescription('Halo Infinite - Leaf Map Overviews');

        return view('pages.overviews', [
            'type' => $filterType,
        ]);
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
