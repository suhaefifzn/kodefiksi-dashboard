<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:120,1')
    ->group(function() {
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
                        Route::get('', 'index')->name('articles.index');
                        Route::post('', 'store')->name('articles.add');
                        Route::put('/update', 'update')->name('articles.update');
                        Route::get('/table', 'renderTable')->name('articles.table');
                        Route::get('/create', 'create')->name('articles.create');
                        Route::get('/create', 'create')->name('articles.create');
                        Route::delete('/delete', 'delete')->name('articles.delete');
                        Route::get('/body/images', 'getDataTableBodyImages')->name('articles.body.images.table');
                        Route::post('/body/images', 'storeBodyImages')->name('articles.body.images.add');
                        Route::post('/slug/generate', 'generateSlug')->name('articles.slug.generate');
                        Route::get('/edit/{slug}', 'edit')->name('articles.edit');
                        Route::get('/{slug}', 'detail')->name('articles.detail');
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
    });

