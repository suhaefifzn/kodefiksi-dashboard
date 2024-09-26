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
                Route::post('', 'store')->name('categories.add');
                Route::delete('/delete', 'delete')->name('categories.delete');
                Route::put('/edit/{slug}', 'updateCategoryName')->name('categories.edit.name');
                Route::get('/list', 'getCategories')->name('categories.get.list');
                Route::get('/table', 'renderTable')->name('categories.get.table');
                Route::get('/{slug}', 'getDetailCategory')->name('categories.get.detail');
            });

        /**
         * Dashboard - Users
         */
        Route::controller(UserController::class)
            ->prefix('users')
            ->middleware('admin')
            ->group(function () {
                Route::get('', 'index')->name('users');
                Route::post('', 'store')->name('users.add');
                Route::get('/table', 'dataTable')->name('users.table');
                Route::delete('/delete', 'delete')->name('users.delete');
                Route::put('/password', 'changePassword')->name('users.change.password');
                Route::put('/profile/{username}', 'changeProfile')->name('users.change.profile');
                Route::get('/profile/{username}', 'getProfile')->name('users.get.profile');
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
