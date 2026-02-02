<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class TestCompanyInfoRoute extends Command
{
    protected $signature = 'test:company-info-route';
    protected $description = 'Test company info route and middleware';

    public function handle()
    {
        $this->info("=== Testing Company Info Routes ===\n");
        
        // Check routes
        $routes = Route::getRoutes();
        $companyRoutes = collect($routes)->filter(function ($route) {
            return str_contains($route->getName() ?? '', 'company-info');
        });
        
        $this->info("Found " . $companyRoutes->count() . " company-info routes:");
        foreach ($companyRoutes as $route) {
            $this->line("- {$route->methods()[0]} {$route->uri()} => {$route->getName()}");
            $this->line("  Middleware: " . implode(', ', $route->middleware()));
        }
        
        // Check cache for rate limiting
        $this->info("\n=== Checking Rate Limit Cache ===");
        $ip = '127.0.0.1';
        
        $keys = [
            "admin_burst:{$ip}",
            "admin_minute:{$ip}:18",
            "admin_hour:{$ip}:18",
            "admin_failed:{$ip}",
            "admin_violations:{$ip}",
            "admin_ddos_block:{$ip}",
        ];
        
        foreach ($keys as $key) {
            $value = Cache::get($key);
            if ($value) {
                $this->warn("  {$key}: {$value}");
            } else {
                $this->line("  {$key}: (not set)");
            }
        }
        
        // Check middleware registration
        $this->info("\n=== Checking Middleware Registration ===");
        $middlewares = [
            'auth',
            'admin.ddos',
            'idle.timeout',
            'menu.permission'
        ];
        
        foreach ($middlewares as $middleware) {
            $registered = app('router')->hasMiddleware($middleware);
            if ($registered) {
                $this->info("  ✓ {$middleware}");
            } else {
                $this->error("  ✗ {$middleware} NOT REGISTERED");
            }
        }
        
        return 0;
    }
}
