<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CountDownController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WatchListController;
use App\Http\Controllers\ReportController;

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

Route::get('/', [AuthenticatedSessionController::class,'create'])->middleware('auth');
Route::post('/', [AuthenticatedSessionController::class, 'store'])->middleware('auth');

Route::resource('countDown',CountDownController::class)->middleware('auth');
Route::resource('shows',ShowController::class)->middleware('auth');
Route::resource('follows',FollowController::class)->middleware('auth');
Route::resource('ratings',RatingController::class)->middleware('auth');
Route::resource('watchlist',WatchListController::class)->middleware('auth');
Route::resource('admin',RoleController::class)->middleware('auth');
Route::resource('report',ReportController::class)->middleware('auth');

require __DIR__.'/auth.php';


Route::fallback(function () {
    abort(404);
});
