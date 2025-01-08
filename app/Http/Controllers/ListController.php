<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\View\View;

class ListController extends Controller
{
    public function banned(): View
    {
        SEOTools::setTitle(e('Banned Players'));
        SEOTools::setDescription(e('Halo Infinite - Banned Players'));

        return view('pages.lists.banned');
    }
}
