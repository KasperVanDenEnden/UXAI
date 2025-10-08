<?php

use App\Http\Controllers\AppModeController;
use App\Http\Controllers\BadController;
use App\Http\Controllers\GoodController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'good.index');
Route::view('/info', 'info')->name('info');

Route::post('/app-mode', [AppModeController::class, 'toggle'])->name('app-mode');
Route::resource('/good', GoodController::class)->only(['index', 'create', 'store']);
Route::resource('/bad', BadController::class)->only(['index', 'create', 'store']);
