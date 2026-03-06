<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CspReportController extends Controller
{
    /**
     * Handle CSP violation reports
     */
    public function report(Request $request)
    {
        $report = $request->input('csp-report');

        if ($report) {
            Log::channel('security')->warning('CSP Violation', [
                'document-uri' => $report['document-uri'] ?? null,
                'violated-directive' => $report['violated-directive'] ?? null,
                'blocked-uri' => $report['blocked-uri'] ?? null,
                'source-file' => $report['source-file'] ?? null,
                'line-number' => $report['line-number'] ?? null,
                'column-number' => $report['column-number'] ?? null,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return response('', 204);
    }
}
