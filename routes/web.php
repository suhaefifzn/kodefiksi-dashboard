<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

/**
 * Dashboard - Home
 */
Route::controller(HomeController::class)
    ->prefix('home')
    ->group(function () {
        Route::get('', 'index')->name('home');
    });
