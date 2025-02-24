<?php

use App\Http\Controllers\Auth\BaseAuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HcsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\ScrimController;
use App\Http\Controllers\Webhook\FaceItController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

// Webhook
Route::post('/webhooks/faceit', FaceItController::class)->name('webhooks.faceit');

// Sitemaps
Route::redirect('sitemap.xml', '/sitemaps/sitemap.xml', Response::HTTP_PERMANENTLY_REDIRECT);

// Leaderboard
Route::get('/leaderboards/top-ten', [LeaderboardController::class, 'topTenList'])->name('topTenLeaderboards');
Route::get('/leaderboards/top-ten/{key}', [LeaderboardController::class, 'topTen'])->name('topTenLeaderboard');
Route::get('/leaderboards/medal', [LeaderboardController::class, 'medalList'])->name('medalLeaderboards');
Route::get('/leaderboards/medal/{medal}', [LeaderboardController::class, 'medal'])->name('medalLeaderboard');

// Scrim
Route::pattern('scrimType', 'overview|matches|players');
Route::get('/scrims/{scrim}/players/csv', [ScrimController::class, 'csvPlayers'])->name('scrimPlayersCsv');
Route::get('/scrims/{scrim}/{scrimType?}', [ScrimController::class, 'show'])->name('scrim');
Route::get('/scrims', [ScrimController::class, 'index'])->name('scrims');

// Player
Route::middleware(['throttle:ban'])->group(function () {
    Route::get('/player/{player}/ban-check', [PlayerController::class, 'banCheck'])->name('banCheck');
});
Route::post('/player/{player}/link', [PlayerController::class, 'link'])->name('playerLink');
Route::post('/player/{player}/unlink', [PlayerController::class, 'unlink'])->name('playerUnlink');
Route::pattern('type', 'overview|medals|competitive|matches|custom|lan|modes');

Route::middleware(['throttle:uploads'])->group(function () {
    Route::get('/player/{player}/matches/csv/{type}', [PlayerController::class, 'csv'])->name('historyCsv');
});

Route::get('/player/{player}/{type?}', [PlayerController::class, 'index'])->name('player');
Route::redirect('/profile/{player}', '/player/{player}');

// Game
Route::get('/game/{game}/csv', [GameController::class, 'csv'])->name('gameCsv');
Route::get('/game/{game}', [GameController::class, 'index'])->name('game');

// HCS
Route::get('/hcs/{championship}/matchup/{matchup}', [HcsController::class, 'matchup'])->name('matchup');
Route::get('/hcs/{championship}/{bracket?}/{round?}', [HcsController::class, 'championship'])
    ->whereNumber('round')
    ->name('championship');
Route::get('/hcs', [HcsController::class, 'index'])->name('championships');

// Playlists
Route::get('/playlists/{playlist?}/{tab?}', [PlaylistController::class, 'index'])->name('playlist');
Route::pattern('tab', 'overview|stats');

// Ranks
Route::get('/ranks', RankController::class)->name('ranks');

// Overviews
Route::get('/overviews/{filterType?}', [OverviewController::class, 'list'])->name('overviews');
Route::pattern('filterType', implode('|', \App\Enums\OverviewType::getValues()));
Route::get('/overview/{overview}/{tab?}', [OverviewController::class, 'show'])->name('overview');
Route::pattern('tab', implode('|', \App\Enums\OverviewTab::getValues()));

// Auth
Route::redirect('/login', '/auth/google/redirect')->name('login');
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('googleRedirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('googleCallback');
Route::post('/auth/logout', [BaseAuthController::class, 'logout'])->name('logout');

// Lists
Route::get('/lists/banned', [ListController::class, 'banned'])->name('bannedList');

// Home
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/legal', [HomeController::class, 'legal'])->name('legal');
Route::get('/', [HomeController::class, 'index'])->name('home');
