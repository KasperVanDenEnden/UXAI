<?php

use App\Http\Controllers\AppModeController;
use App\Http\Controllers\BadController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\InfoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'good.index');
Route::get('/info', InfoController::class)->name('info');

Route::post('/app-mode', [AppModeController::class, 'toggle'])->name('app-mode');
Route::resource('/good', GoodController::class)->only(['index', 'create', 'store']);
Route::resource('/bad', BadController::class)->only(['index', 'create', 'store']);
