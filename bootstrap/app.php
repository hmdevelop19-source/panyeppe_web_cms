<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
        
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// PAKSA JALUR PUBLIK DISINI
if ($publicPath = env('PUBLIC_PATH')) {
    $app->usePublicPath(realpath(base_path($publicPath)) ?: base_path($publicPath));
}

return $app;
