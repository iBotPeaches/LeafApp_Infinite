<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Playlist;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PlaylistController extends Controller
{
    public function index(Request $request, ?Playlist $playlist = null): View
    {
        /** @var Playlist|null $playlist */
        $playlist ??= Playlist::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if (! $playlist) {
            abort(Response::HTTP_NOT_FOUND);
        }

        SEOTools::setTitle($playlist->name);
        SEOTools::setDescription($playlist->title);

        $playlists = Playlist::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.playlist', [
            'user' => $request->user(),
            'playlist' => $playlist,
            'playlists' => $playlists,
        ]);
    }
}
