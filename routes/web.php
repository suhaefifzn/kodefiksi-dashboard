<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

/**
 * Authentications
 */
Route::controller(AuthController::class)
    ->group(function () {
        Route::get('login', 'index')->name('auth.index')->middleware('guest');
        Route::post('login', 'login')->name('auth.login')->middleware('guest');
        Route::get('logout', 'logout')->name('auth.logout')->middleware('auth.token');
    });

Route::middleware('auth.token')
    ->group(function () {
        /**
         * Dashboard - Home
         */
        Route::controller(HomeController::class)
            ->prefix('home')
            ->group(function () {
                Route::get('', 'index')->name('home');
            });

        /**
         * Dashboard - Articles
         */
        Route::controller(ArticleController::class)
            ->prefix('articles')
            ->group(function () {
                Route::get('published', 'getPublishedList')->name('articles.publish');
                Route::get('draft', 'getDraftList')->name('articles.draft');
            });

        /**
         * Dashboard - Categories
         */
        Route::controller(CategoryController::class)
            ->prefix('categories')
            ->middleware('admin')
            ->group(function () {
                Route::get('', 'index')->name('categories');
            });

        /**
         * Dashboard - Users
         */
        Route::controller(UserController::class)
            ->prefix('users')
            ->middleware('admin')
            ->group(function () {
                Route::get('', 'index')->name('users');
            });

        /**
         * Dashboard - Profile
         */
        Route::controller(ProfilController::class)
            ->prefix('profile')
            ->group(function () {
                Route::get('', 'index')->name('profile');
                Route::post('/upload-image', 'updateImage')->name('profile.edit.image');
                Route::put('/edit', 'updateProfileData')->name('profile.edit.data');
                Route::put('/edit/password', 'updatePasswordAccount')->name('profile.edit.password');
            });
    });
