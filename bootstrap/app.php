<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'throttle:120,1'])
                ->group(base_path('routes/hero-slider-routes.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'throttle.custom' => \App\Http\Middleware\RateLimitRequests::class,
            'security.block' => \App\Http\Middleware\BlockSuspiciousRequests::class,
            'admin.ddos' => \App\Http\Middleware\AdminDdosProtection::class,
            'ddos' => \App\Http\Middleware\DdosProtection::class,
            'menu.permission' => \App\Http\Middleware\CheckMenuPermission::class,
            'idle.timeout' => \App\Http\Middleware\IdleTimeoutMiddleware::class,
        ]);

        // Security headers for all responses
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Web middleware group - DDoS protection runs first
        $middleware->web(append: [
            \App\Http\Middleware\DdosProtection::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\BlockSuspiciousRequests::class,
            \App\Http\Middleware\IdleTimeoutMiddleware::class,
            \App\Http\Middleware\LogVisitor::class,
            \App\Http\Middleware\OptimizeResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
