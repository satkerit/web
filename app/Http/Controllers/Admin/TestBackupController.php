<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestBackupController extends Controller
{
    public function index()
    {
        try {
            // Test basic functionality
            $data = [
                'message' => 'Test controller works',
                'database_config' => config('database.connections.mysql'),
                'storage_path' => storage_path('app/backups/database'),
                'directory_exists' => is_dir(storage_path('app/backups/database')),
                'directory_writable' => is_writable(storage_path('app/backups/database')),
            ];

            return view('admin.test-backup', compact('data'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
