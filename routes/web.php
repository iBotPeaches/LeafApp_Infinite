<?php

use App\Http\Controllers\Auth\BaseAuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ScrimController;
use App\Http\Controllers\Webhook\FaceItController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HcsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Webhook
Route::post('/webhooks/faceit', FaceItController::class)->name('webhooks.faceit');

// Leaderboard
Route::get('/leaderboards/medal/{medal}', [LeaderboardController::class, 'medal'])->name('medalLeaderboard');

// Scrim
Route::get('/scrims/{scrim}/{type?}', [ScrimController::class, 'show'])->name('scrim');
Route::get('/scrims', [ScrimController::class, 'index'])->name('scrims');

// Player
Route::post('/player/{player}/link', [PlayerController::class, 'link'])->name('playerLink');
Route::post('/player/{player}/unlink', [PlayerController::class, 'unlink'])->name('playerUnlink');
Route::get('/player/{player}/matches/csv', [PlayerController::class, 'csv'])->name('historyCsv');
Route::pattern('type', 'overview|medals|competitive|matches|custom');
Route::get('/player/{player}/{type?}', [PlayerController::class, 'index'])->name('player');
Route::redirect('/profile/{player}', '/player/{player}');

// Game
Route::get('/game/{game}/csv', [GameController::class, 'csv'])->name('gameCsv');
Route::get('/game/{game}', [GameController::class, 'index'])->name('game');

// HCS
Route::get('/hcs/{championship}/matchup/{matchup}', [HcsController::class, 'matchup'])->name('matchup');
Route::get('/hcs/{championship}/{bracket?}/{round?}', [HcsController::class, 'championship'])->name('championship');
Route::get('/hcs', [HcsController::class, 'index'])->name('championships');

// Auth
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('googleRedirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('googleCallback');
Route::post('/auth/logout', [BaseAuthController::class, 'logout'])->name('logout');

// Home
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/legal', [HomeController::class, 'legal'])->name('legal');
Route::get('/', [HomeController::class, 'index'])->name('home');
