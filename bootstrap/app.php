<?php

use App\Http\Middleware\guest;
use App\Http\Middleware\hasToken;
use App\Http\Middleware\isAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('auth.token', [
            hasToken::class,
        ]);
        $middleware->appendToGroup('guest', [
            guest::class,
        ]);
        $middleware->appendToGroup('admin', [
            isAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
