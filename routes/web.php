<?php

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

// Player
Route::get('/player/{player}/matches/csv', [PlayerController::class, 'csv'])->name('historyCsv');
Route::pattern('type', 'overview|competitive|matches|custom');
Route::get('/player/{player}/{type?}', [PlayerController::class, 'index'])->name('player');
Route::redirect('/profile/{player}', '/player/{player}');

// Game
Route::get('/game/{game}', [GameController::class, 'index'])->name('game');

// HCS
Route::get('/hcs/{championship}/matchup/{matchup}', [HcsController::class, 'matchup'])->name('matchup');
Route::get('/hcs/{championship}/{bracket?}/{round?}', [HcsController::class, 'championship'])->name('championship');
Route::get('/hcs', [HcsController::class, 'index'])->name('championships');

// Home
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/', [HomeController::class, 'index'])->name('home');
