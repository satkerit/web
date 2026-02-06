<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SimpleBackupController extends Controller
{
    use AuthorizesAdminActions;

    protected $backupPath = 'backups/database';

    public function index()
    {
        $this->authorizeView('storage.view');

        $backups = $this->getBackupFiles();
        $databaseInfo = $this->getDatabaseInfo();
        $storageInfo = $this->getStorageInfo();

        return view('admin.simple-backup.index', compact('backups', 'databaseInfo', 'storageInfo'));
    }

    public function create(Request $request)
    {
        $this->authorizeEdit('storage.manage');

        try {
            $filename = 'simple_backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
            $backupPath = $this->createSimpleBackup($filename);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat.',
                'filename' => $filename,
                'size' => $this->formatFileSize(File::size($backupPath))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function createSimpleBackup($filename)
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));
        $backupDir = storage_path("app/{$this->backupPath}");

        // Ensure backup directory exists
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $backupPath = $backupDir . '/' . $filename;

        // Get all tables
        $tables = DB::select('SHOW FULL TABLES');
        $tableKey = 'Tables_in_' . $dbConfig['database'];

        $sql = "-- Simple Backup Created: " . now()->toDateTimeString() . "\n";
        $sql .= "-- Database: " . $dbConfig['database'] . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $tableType = $table->Table_type;

            // Get CREATE TABLE statement
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");

            if ($tableType === 'VIEW') {
                $sql .= "DROP VIEW IF EXISTS `{$tableName}`;\n";
            } else {
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            }

            if (!empty($createTable)) {
                $createRow = (array) $createTable[0];
                $createSql = $createRow['Create Table'] ?? $createRow['Create View'] ?? null;

                if ($createSql) {
                    $sql .= $createSql . ";\n\n";
                }
            }

            // Get table data
            if ($tableType === 'BASE TABLE') {
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "INSERT INTO `{$tableName}` VALUES\n";
                    $values = [];
                    foreach ($rows as $row) {
                        $rowData = [];
                        foreach ($row as $value) {
                            if (is_null($value)) {
                                $rowData[] = 'NULL';
                            } else {
                                $rowData[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $values[] = '(' . implode(',', $rowData) . ')';
                    }
                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }
        }

        File::put($backupPath, $sql);
        return $backupPath;
    }

    protected function getBackupFiles()
    {
        $backupDir = storage_path("app/{$this->backupPath}");

        if (!File::exists($backupDir)) {
            return collect();
        }

        $files = File::files($backupDir);
        $backups = collect();

        foreach ($files as $file) {
            $filename = $file->getFilename();

            if (!str_ends_with($filename, '.sql')) {
                continue;
            }

            $backups->push([
                'filename' => $filename,
                'size' => $file->getSize(),
                'size_formatted' => $this->formatFileSize($file->getSize()),
                'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
            ]);
        }

        return $backups->sortByDesc('created_at');
    }

    protected function getDatabaseInfo()
    {
        $dbConfig = Config::get('database.connections.' . Config::get('database.default'));

        try {
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);

            return [
                'name' => $dbConfig['database'],
                'host' => $dbConfig['host'],
                'port' => $dbConfig['port'],
                'table_count' => $tableCount,
            ];
        } catch (\Exception $e) {
            return [
                'name' => $dbConfig['database'],
                'host' => $dbConfig['host'],
                'port' => $dbConfig['port'],
                'table_count' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    protected function getStorageInfo()
    {
        $backupDir = storage_path("app/{$this->backupPath}");

        if (!File::exists($backupDir)) {
            return [
                'total_backups' => 0,
                'total_size' => 0,
                'total_size_formatted' => '0 B',
            ];
        }

        $files = File::files($backupDir);
        $totalSize = 0;
        $backupCount = 0;

        foreach ($files as $file) {
            if (str_ends_with($file->getFilename(), '.sql')) {
                $totalSize += $file->getSize();
                $backupCount++;
            }
        }

        return [
            'total_backups' => $backupCount,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatFileSize($totalSize),
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
}
