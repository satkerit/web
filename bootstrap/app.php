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

            // Explicit route model binding for WhyChooseUs
            Route::bind('why_choose_us', function ($value) {
                return \App\Models\WhyChooseUs::findOrFail($value);
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Set middleware priority - optimize upload needs to run early
        $middleware->priority([
            \App\Http\Middleware\OptimizeFileUpload::class,
            \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Register middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'throttle.custom' => \App\Http\Middleware\RateLimitRequests::class,
            'security.block' => \App\Http\Middleware\BlockSuspiciousRequests::class,
            'admin.ddos' => \App\Http\Middleware\AdminDdosProtection::class,
            'ddos' => \App\Http\Middleware\DdosProtection::class,
            'menu.permission' => \App\Http\Middleware\CheckMenuPermission::class,
            'idle.timeout' => \App\Http\Middleware\IdleTimeoutMiddleware::class,
            'detect.suspicious' => \App\Http\Middleware\DetectSuspiciousActivity::class,
            'secure.session' => \App\Http\Middleware\SecureSessionMiddleware::class,
            'optimize.upload' => \App\Http\Middleware\OptimizeFileUpload::class,
        ]);


        // Web middleware group - Security monitoring runs early
        $middleware->web(append: [
            \App\Http\Middleware\OptimizeFileUpload::class,
            \App\Http\Middleware\CacheStaticAssets::class,
            \App\Http\Middleware\DdosProtection::class,
            \App\Http\Middleware\DetectSuspiciousActivity::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\BlockSuspiciousRequests::class,
            \App\Http\Middleware\SecureSessionMiddleware::class,
            \App\Http\Middleware\LogVisitor::class,
            \App\Http\Middleware\OptimizeResponse::class,
            // \Spatie\ResponseCache\Middlewares\CacheResponse::class, // Temporarily disabled - run: composer install && php artisan config:clear on production
        ]);

        // Security headers for all responses
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->validateCsrfTokens(except: [
            '/api/csp-report',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
