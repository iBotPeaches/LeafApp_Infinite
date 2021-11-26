<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;

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

// Player
Route::pattern('type', 'overview|competitive|matches');
Route::get('/player/{player}/{type?}', [PlayerController::class, 'index'])->name('player');

// Home
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/', [HomeController::class, 'index'])->name('home');
