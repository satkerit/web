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
            if (!request()->expectsJson()) {
                return view('admin.database-backup.simple', [
                    'backups' => [],
                    'databaseInfo' => [],
                    'storageInfo' => [],
                    'error' => $e->getMessage(),
                ]);
            }

            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

    public function create(Request $request)
    {
        $this->authorizeEdit('storage.manage');

        $request->validate([
            'backup_type' => 'required|in:full,structure_only,data_only',
            'compression' => 'boolean',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $backupType = $request->input('backup_type', 'full');
            $compression = $request->boolean('compression', true);
            $description = $request->input('description', '');

            $dbName = Config::get('database.connections.' . Config::get('database.default'))['database'];
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$dbName}_{$backupType}_{$timestamp}.sql" . ($compression ? '.gz' : '');

            $backupDir = storage_path("app/{$this->backupPath}");
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $backupPath = $backupDir . '/' . $filename;
            $sqlContent = $this->generatePdoBackup($backupType, $description);

            if ($compression) {
                File::put($backupPath, gzencode($sqlContent, 9));
            } else {
                File::put($backupPath, $sqlContent);
            }

            $fileSize = File::size($backupPath);

            AuditTrail::log('database_backup', "Database backup created: {$filename}", null, null, [
                'backup_type' => $backupType,
                'compression' => $compression,
                'file_size' => $fileSize,
                'description' => $description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup database berhasil dibuat.',
                'filename' => $filename,
                'size' => format_file_size($fileSize),
                'download_url' => route('admin.database-backup.download', ['filename' => $filename]),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat backup: ' . $e->getMessage()], 500);
        }
    }

    public function download(Request $request, $filename)
    {
        $this->authorizeView('storage.view');

        $filename = basename($filename);
        $filePath = storage_path("app/{$this->backupPath}/{$filename}");

        if (!File::exists($filePath)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        AuditTrail::log('database_backup_download', "Database backup downloaded: {$filename}");

        $contentType = str_ends_with($filename, '.sql.gz') ? 'application/gzip'
            : (str_ends_with($filename, '.sql') ? 'application/sql' : 'application/octet-stream');

        return response()->download($filePath, $filename, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function delete(Request $request, $filename)
    {
        $this->authorizeDelete('storage.manage');

        $filename = basename($filename);
        $filePath = storage_path("app/{$this->backupPath}/{$filename}");

        if (!File::exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File backup tidak ditemukan.'], 404);
        }

        try {
            File::delete($filePath);
            AuditTrail::log('database_backup_delete', "Database backup deleted: {$filename}");

            return response()->json(['success' => true, 'message' => 'Backup berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus backup: ' . $e->getMessage()], 500);
        }
    }

    public function cleanup(Request $request)
    {
        $this->authorizeDelete('storage.manage');

        $request->validate(['days' => 'required|integer|min:1|max:365']);

        $cutoffDate = now()->subDays($request->input('days'));
        $deletedCount = 0;
        $deletedSize = 0;

        foreach ($this->getBackupFiles() as $backup) {
            if ($backup['created_at']->lt($cutoffDate)) {
                $filePath = storage_path("app/{$this->backupPath}/{$backup['filename']}");
                if (File::exists($filePath)) {
                    $deletedSize += File::size($filePath);
                    File::delete($filePath);
                    $deletedCount++;
                }
            }
        }

        AuditTrail::log('database_backup_cleanup', "Cleaned up {$deletedCount} old backups", null, null, [
            'days' => $request->input('days'),
            'deleted_count' => $deletedCount,
            'deleted_size' => $deletedSize,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menghapus {$deletedCount} backup lama (" . format_file_size($deletedSize) . ').',
        ]);
    }

    public function getBackupFiles()
    {
        $backupDir = storage_path("app/{$this->backupPath}");

        if (!File::exists($backupDir)) {
            return collect();
        }

        return collect(File::files($backupDir))
            ->filter(fn($file) => preg_match('/\.(sql|sql\.gz)$/', $file->getFilename()))
            ->map(fn($file) => [
                'filename' => $file->getFilename(),
                'size' => $file->getSize(),
                'size_formatted' => format_file_size($file->getSize()),
                'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                'type' => $this->getBackupTypeFromFilename($file->getFilename()),
                'compressed' => str_ends_with($file->getFilename(), '.gz'),
                'metadata' => $this->getBackupMetadata($file->getPathname()),
            ])
            ->sortByDesc('created_at')
            ->values();
    }

    protected function getBackupTypeFromFilename($filename)
    {
        if (strpos($filename, '_full_') !== false) return 'full';
        if (strpos($filename, '_structure_only_') !== false) return 'structure_only';
        if (strpos($filename, '_data_only_') !== false) return 'data_only';
        return 'unknown';
    }

    protected function getBackupMetadata($filePath)
    {
        $isCompressed = pathinfo($filePath, PATHINFO_EXTENSION) === 'gz';
        $content = $isCompressed ? gzdecode(File::get($filePath)) : File::get($filePath);

        if (preg_match('/-- Backup Metadata:(.*?)-- End Metadata/s', $content, $matches)) {
            $metadata = [];
            foreach (explode("\n", trim($matches[1])) as $line) {
                if (preg_match('/-- (\w+): (.+)/', $line, $m)) {
                    $metadata[strtolower($m[1])] = $m[2];
                }
            }
            return $metadata;
        }

        return [];
    }

    public function getDatabaseInfo()
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));

        try {
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            $tableKey = 'Tables_in_' . $dbConfig['database'];

            $sizeQuery = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
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
                'size_formatted' => format_file_size($sizeMB * 1024 * 1024),
            ];
        } catch (\Exception $e) {
            return [
                'name' => $dbConfig['database'],
                'host' => $dbConfig['host'],
                'port' => $dbConfig['port'],
                'table_count' => 'N/A',
                'size_mb' => 0,
                'size_formatted' => 'N/A',
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
                'available_space_formatted' => format_file_size(disk_free_space(storage_path())),
            ];
        }

        $totalSize = 0;
        $backupCount = 0;

        foreach (File::files($backupDir) as $file) {
            if (preg_match('/\.(sql|sql\.gz)$/', $file->getFilename())) {
                $totalSize += $file->getSize();
                $backupCount++;
            }
        }

        return [
            'total_backups' => $backupCount,
            'total_size' => $totalSize,
            'total_size_formatted' => format_file_size($totalSize),
            'available_space' => disk_free_space(storage_path()),
            'available_space_formatted' => format_file_size(disk_free_space(storage_path())),
        ];
    }

    protected function generatePdoBackup($backupType, $description = '')
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));
        $dbName = $dbConfig['database'];

        $sql = "-- Backup Metadata:\n";
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

        $tables = DB::select('SHOW FULL TABLES');
        $tableKey = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $tableType = $table->Table_type;

            if ($backupType !== 'data_only') {
                $sql .= "-- Structure for `{$tableName}`\n\n";

                $sql .= ($tableType === 'VIEW')
                    ? "DROP VIEW IF EXISTS `{$tableName}`;\n"
                    : "DROP TABLE IF EXISTS `{$tableName}`;\n";

                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createTable)) {
                    $createRow = (array) $createTable[0];
                    $createSql = $createRow['Create Table'] ?? $createRow['Create View'] ?? null;
                    if ($createSql) {
                        $sql .= $createSql . ";\n\n";
                    }
                }
            }

            if ($backupType !== 'structure_only' && $tableType === 'BASE TABLE') {
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "-- Dumping data for table `{$tableName}`\n\n";
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $columns = array_keys($rowArray);
                        $values = array_map(fn($v) => is_null($v) ? 'NULL' : DB::getPdo()->quote($v), array_values($rowArray));
                        $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\nCOMMIT;\n";

        return $sql;
    }
}