<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // WEB middleware default (cukup kosongkan)
        $middleware->web(append: [
            //
        ]);

        // API middleware - HAPUS EnsureFrontendRequestsAreStateful untuk mobile apps
        $middleware->api(prepend: [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // ❌ Disabled for mobile
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

    })



    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
