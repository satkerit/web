<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseBackupController extends Controller
{
    use AuthorizesAdminActions;

    protected $backupPath = 'backups/database';

    public function index()
    {
        $this->authorizeView('storage.view');

        try {
            $backups = $this->getBackupFiles();
            $databaseInfo = $this->getDatabaseInfo();
            $storageInfo = $this->getStorageInfo();

            return view('admin.database-backup.simple', compact('backups', 'databaseInfo', 'storageInfo'));
        } catch (\Exception $e) {
            // For web requests, show error page instead of JSON
            if (!request()->expectsJson()) {
                return view('admin.database-backup.simple', [
                    'backups' => [],
                    'databaseInfo' => [],
                    'storageInfo' => [],
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $this->authorizeEdit('storage.manage');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: ' . $e->getMessage()
            ], 403);
        }

        try {
            $request->validate([
                'backup_type' => 'required|in:full,structure_only,data_only',
                'compression' => 'boolean',
                'description' => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        }

        try {
            $backupType = $request->input('backup_type', 'full');
            $compression = $request->boolean('compression', true);
            $description = $request->input('description', '');

            $dbConfig = Config::get('database.connections.' . Config::get('database.default'));
            $dbName = $dbConfig['database'];

            // Generate filename
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$dbName}_{$backupType}_{$timestamp}.sql";
            if ($compression) {
                $filename .= '.gz';
            }

            $backupDir = storage_path("app/{$this->backupPath}");

            // Ensure backup directory exists
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $backupPath = $backupDir . '/' . $filename;

            // Generate SQL backup using PDO (no mysqldump required)
            $sqlContent = $this->generatePdoBackup($backupType, $description);

            // Save to file (with compression if enabled)
            if ($compression) {
                File::put($backupPath, gzencode($sqlContent, 9));
            } else {
                File::put($backupPath, $sqlContent);
            }

            $fileSize = File::size($backupPath);

            // Log backup activity
            AuditTrail::log('database_backup', "Database backup created: {$filename}", null, null, [
                'backup_type' => $backupType,
                'compression' => $compression,
                'file_size' => $fileSize,
                'description' => $description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup database berhasil dibuat.',
                'filename' => $filename,
                'size' => $this->formatFileSize($fileSize),
                'download_url' => route('admin.database-backup.download', ['filename' => $filename])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Request $request, $filename)
    {
        $this->authorizeView('storage.view');

        // Sanitize filename
        $filename = basename($filename);

        $filePath = storage_path("app/{$this->backupPath}/{$filename}");

        if (!File::exists($filePath)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        // Log download activity
        AuditTrail::log('database_backup_download', "Database backup downloaded: {$filename}");

        // Determine content type based on file extension
        $contentType = 'application/octet-stream';
        if (str_ends_with($filename, '.sql')) {
            $contentType = 'application/sql';
        } elseif (str_ends_with($filename, '.sql.gz')) {
            $contentType = 'application/gzip';
        }

        return response()->download($filePath, $filename, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function delete(Request $request, $filename)
    {
        $this->authorizeDelete('storage.manage');

        // Sanitize filename
        $filename = basename($filename);

        $filePath = storage_path("app/{$this->backupPath}/{$filename}");

        if (!File::exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File backup tidak ditemukan.'
            ], 404);
        }

        try {
            File::delete($filePath);

            // Log delete activity
            AuditTrail::log('database_backup_delete', "Database backup deleted: {$filename}");

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request, $filename)
    {
        $this->authorizeEdit('storage.manage');

        // Sanitize filename
        $filename = basename($filename);

        $filePath = storage_path("app/{$this->backupPath}/{$filename}");

        if (!File::exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File backup tidak ditemukan.'
            ], 404);
        }

        try {
            $this->restoreBackup($filePath);

            // Log restore activity
            AuditTrail::log('database_backup_restore', "Database restored from backup: {$filename}");

            return response()->json([
                'success' => true,
                'message' => 'Database berhasil direstore dari backup.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal restore database: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cleanup(Request $request)
    {
        $this->authorizeDelete('storage.manage');

        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $days = $request->input('days');
        $cutoffDate = now()->subDays($days);
        $deletedCount = 0;
        $deletedSize = 0;

        $backups = $this->getBackupFiles();

        foreach ($backups as $backup) {
            if ($backup['created_at']->lt($cutoffDate)) {
                $filePath = storage_path("app/{$this->backupPath}/{$backup['filename']}");
                if (File::exists($filePath)) {
                    $deletedSize += File::size($filePath);
                    File::delete($filePath);
                    $deletedCount++;
                }
            }
        }

        // Log cleanup activity
        AuditTrail::log('database_backup_cleanup', "Cleaned up {$deletedCount} old backups", null, null, [
            'days' => $days,
            'deleted_count' => $deletedCount,
            'deleted_size' => $deletedSize
        ]);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menghapus {$deletedCount} backup lama ({$this->formatFileSize($deletedSize)})."
        ]);
    }

    protected function createBackup($type, $filename, $compression = true, $description = '')
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));
        $backupDir = storage_path("app/{$this->backupPath}");

        // Ensure backup directory exists
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $backupPath = $backupDir . '/' . $filename;

        // Build mysqldump command
        $command = [
            'mysqldump',
            '--host=' . $dbConfig['host'],
            '--port=' . $dbConfig['port'],
            '--user=' . $dbConfig['username'],
        ];

        if (!empty($dbConfig['password'])) {
            $command[] = '--password=' . $dbConfig['password'];
        }

        // Add backup type options
        switch ($type) {
            case 'structure_only':
                $command[] = '--no-data';
                break;
            case 'data_only':
                $command[] = '--no-create-info';
                break;
            case 'full':
            default:
                // Full backup (default)
                break;
        }

        // Add additional options
        $command = array_merge($command, [
            '--single-transaction',
            '--routines',
            '--triggers',
            '--add-drop-table',
            '--extended-insert',
            '--quick',
            '--lock-tables=false',
            $dbConfig['database']
        ]);

        // Execute mysqldump
        $process = new Process($command);
        $process->setTimeout(300); // 5 minutes timeout
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $sqlContent = $process->getOutput();

        // Add metadata header
        $metadata = $this->generateBackupMetadata($type, $description);
        $sqlContent = $metadata . "\n" . $sqlContent;

        // Save to file
        if ($compression && pathinfo($filename, PATHINFO_EXTENSION) === 'gz') {
            File::put($backupPath, gzencode($sqlContent, 9));
        } else {
            File::put($backupPath, $sqlContent);
        }

        return $backupPath;
    }

    protected function restoreBackup($filePath)
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));

        // Read backup file
        $isCompressed = pathinfo($filePath, PATHINFO_EXTENSION) === 'gz';
        $sqlContent = $isCompressed ? gzdecode(File::get($filePath)) : File::get($filePath);

        // Remove metadata header
        $sqlContent = preg_replace('/^-- Backup Metadata:.*?\n-- End Metadata\n/ms', '', $sqlContent);

        // Create temporary file for mysql import
        $tempFile = tempnam(sys_get_temp_dir(), 'db_restore_');
        File::put($tempFile, $sqlContent);

        try {
            // Build mysql command
            $command = [
                'mysql',
                '--host=' . $dbConfig['host'],
                '--port=' . $dbConfig['port'],
                '--user=' . $dbConfig['username'],
            ];

            if (!empty($dbConfig['password'])) {
                $command[] = '--password=' . $dbConfig['password'];
            }

            $command[] = $dbConfig['database'];

            // Execute mysql import
            $process = new Process($command);
            $process->setInput(File::get($tempFile));
            $process->setTimeout(600); // 10 minutes timeout
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        } finally {
            // Clean up temporary file
            if (File::exists($tempFile)) {
                File::delete($tempFile);
            }
        }
    }

    public function getBackupFiles()
    {
        $backupDir = storage_path("app/{$this->backupPath}");

        if (!File::exists($backupDir)) {
            return collect();
        }

        $files = File::files($backupDir);
        $backups = collect();

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // Skip non-backup files
            if (!preg_match('/\.(sql|sql\.gz)$/', $filename)) {
                continue;
            }

            $backups->push([
                'filename' => $filename,
                'size' => $file->getSize(),
                'size_formatted' => $this->formatFileSize($file->getSize()),
                'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                'type' => $this->getBackupTypeFromFilename($filename),
                'compressed' => str_ends_with($filename, '.gz'),
                'metadata' => $this->getBackupMetadata($file->getPathname())
            ]);
        }

        return $backups->sortByDesc('created_at');
    }

    protected function generateBackupFilename($type, $compression = true)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $dbName = Config::get('database.connections.' . Config::get('database.default'))['database'];

        $filename = "backup_{$dbName}_{$type}_{$timestamp}.sql";

        if ($compression) {
            $filename .= '.gz';
        }

        return $filename;
    }

    protected function generateBackupMetadata($type, $description)
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));

        return "-- Backup Metadata:\n" .
            "-- Created: " . now()->toDateTimeString() . "\n" .
            "-- Database: {$dbConfig['database']}\n" .
            "-- Type: {$type}\n" .
            "-- Laravel Version: " . app()->version() . "\n" .
            "-- PHP Version: " . PHP_VERSION . "\n" .
            "-- User: " . auth()->user()->name . " (" . auth()->user()->email . ")\n" .
            "-- Description: {$description}\n" .
            "-- End Metadata\n";
    }

    protected function getBackupMetadata($filePath)
    {
        $isCompressed = pathinfo($filePath, PATHINFO_EXTENSION) === 'gz';
        $content = $isCompressed ? gzdecode(File::get($filePath)) : File::get($filePath);

        if (preg_match('/-- Backup Metadata:(.*?)-- End Metadata/s', $content, $matches)) {
            $metadata = [];
            $lines = explode("\n", trim($matches[1]));

            foreach ($lines as $line) {
                if (preg_match('/-- (\w+): (.+)/', $line, $lineMatches)) {
                    $metadata[strtolower($lineMatches[1])] = $lineMatches[2];
                }
            }

            return $metadata;
        }

        return [];
    }

    protected function getBackupTypeFromFilename($filename)
    {
        if (strpos($filename, '_full_') !== false) return 'full';
        if (strpos($filename, '_structure_only_') !== false) return 'structure_only';
        if (strpos($filename, '_data_only_') !== false) return 'data_only';
        return 'unknown';
    }

    public function getDatabaseInfo()
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));

        try {
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);

            $sizeQuery = DB::select("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$dbConfig['database']]);

            $sizeMB = $sizeQuery[0]->size_mb ?? 0;

            return [
                'name' => $dbConfig['database'],
                'host' => $dbConfig['host'],
                'port' => $dbConfig['port'],
                'table_count' => $tableCount,
                'size_mb' => $sizeMB,
                'size_formatted' => $this->formatFileSize($sizeMB * 1024 * 1024)
            ];
        } catch (\Exception $e) {
            return [
                'name' => $dbConfig['database'],
                'host' => $dbConfig['host'],
                'port' => $dbConfig['port'],
                'table_count' => 'N/A',
                'size_mb' => 0,
                'size_formatted' => 'N/A'
            ];
        }
    }

    public function getStorageInfo()
    {
        $backupDir = storage_path("app/{$this->backupPath}");

        if (!File::exists($backupDir)) {
            return [
                'total_backups' => 0,
                'total_size' => 0,
                'total_size_formatted' => '0 B',
                'available_space' => disk_free_space(storage_path()),
                'available_space_formatted' => $this->formatFileSize(disk_free_space(storage_path()))
            ];
        }

        $files = File::files($backupDir);
        $totalSize = 0;
        $backupCount = 0;

        foreach ($files as $file) {
            if (preg_match('/\.(sql|sql\.gz)$/', $file->getFilename())) {
                $totalSize += $file->getSize();
                $backupCount++;
            }
        }

        return [
            'total_backups' => $backupCount,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatFileSize($totalSize),
            'available_space' => disk_free_space(storage_path()),
            'available_space_formatted' => $this->formatFileSize(disk_free_space(storage_path()))
        ];
    }

    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }

    /**
     * Generate database backup using PDO (no mysqldump required)
     */
    protected function generatePdoBackup($backupType, $description = '')
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));
        $dbName = $dbConfig['database'];

        $sql = "";

        // Add metadata header
        $sql .= "-- Backup Metadata:\n";
        $sql .= "-- Created: " . now()->toDateTimeString() . "\n";
        $sql .= "-- Database: {$dbName}\n";
        $sql .= "-- Type: {$backupType}\n";
        $sql .= "-- Laravel Version: " . app()->version() . "\n";
        $sql .= "-- PHP Version: " . PHP_VERSION . "\n";
        $sql .= "-- User: " . (auth()->user()->name ?? 'System') . " (" . (auth()->user()->email ?? 'N/A') . ")\n";
        $sql .= "-- Description: {$description}\n";
        $sql .= "-- End Metadata\n\n";

        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $sql .= "SET AUTOCOMMIT = 0;\n";
        $sql .= "START TRANSACTION;\n\n";

        // Get all tables
        $tables = DB::select('SHOW FULL TABLES');
        $tableKey = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $tableType = $table->Table_type;

            // Get table structure
            if ($backupType !== 'data_only') {
                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "-- Structure for `{$tableName}` ({$tableType})\n";
                $sql .= "-- --------------------------------------------------------\n\n";

                if ($tableType === 'VIEW') {
                    $sql .= "DROP VIEW IF EXISTS `{$tableName}`;\n";
                } else {
                    $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                }

                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createTable)) {
                    $createRow = (array) $createTable[0];
                    // Handle both Tables and Views, and different casing
                    $createSql = $createRow['Create Table'] ?? $createRow['Create View'] ?? null;
                    
                    if ($createSql) {
                        $sql .= $createSql . ";\n\n";
                    }
                }
            }

            // Get table data
            if ($backupType !== 'structure_only' && $tableType === 'BASE TABLE') {
                $rows = DB::table($tableName)->get();

                if ($rows->count() > 0) {
                    $sql .= "-- --------------------------------------------------------\n";
                    $sql .= "-- Dumping data for table `{$tableName}`\n";
                    $sql .= "-- --------------------------------------------------------\n\n";

                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $columns = array_keys($rowArray);
                        $values = array_map(function ($value) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            return "'" . addslashes($value) . "'";
                        }, array_values($rowArray));

                        $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $sql .= "COMMIT;\n";

        return $sql;
    }
}
