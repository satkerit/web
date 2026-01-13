<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only log for web routes, not API, admin, or storage
        if (!$request->is('admin/*') && !$request->is('api/*') && !$request->is('livewire/*') && !$request->is('storage/*')) {
            try {
                VisitorLog::logVisit();
            } catch (\Exception $e) {
                // Silently fail - don't break the request
            }
        }

        return $next($request);
    }
}
