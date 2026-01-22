<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KasKelilingFullSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini akan mengimport data master kas keliling dan jadwal lengkap
     * dari file SQL yang ada di database/sql/
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai import data Kas Keliling...');

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Truncate tables (opsional, uncomment jika ingin reset data)
            // $this->command->warn('⚠️  Menghapus data lama...');
            // DB::table('kas_keliling_schedules')->truncate();
            // DB::table('kas_keliling')->truncate();

            // Import master data
            $this->command->info('📋 Mengimport data master kas keliling...');
            $masterSqlPath = database_path('sql/kas_keliling_master_data.sql');
            
            if (File::exists($masterSqlPath)) {
                $masterSql = File::get($masterSqlPath);
                
                // Remove comments and split by semicolon
                $statements = $this->parseSqlFile($masterSql);
                
                foreach ($statements as $statement) {
                    if (!empty(trim($statement))) {
                        DB::unprepared($statement);
                    }
                }
                
                $this->command->info('✅ Data master berhasil diimport');
            } else {
                $this->command->error('❌ File kas_keliling_master_data.sql tidak ditemukan!');
                return;
            }

            // Import schedules
            $this->command->info('📅 Mengimport jadwal kas keliling...');
            $scheduleSqlPath = database_path('sql/kas_keliling_schedules_2026.sql');
            
            if (File::exists($scheduleSqlPath)) {
                $scheduleSql = File::get($scheduleSqlPath);
                
                // Remove comments and split by semicolon
                $statements = $this->parseSqlFile($scheduleSql);
                
                $count = 0;
                foreach ($statements as $statement) {
                    if (!empty(trim($statement))) {
                        DB::unprepared($statement);
                        $count++;
                    }
                }
                
                $this->command->info("✅ {$count} jadwal berhasil diimport");
            } else {
                $this->command->error('❌ File kas_keliling_schedules_2026.sql tidak ditemukan!');
            }

            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Show summary
            $totalAreas = DB::table('kas_keliling')->count();
            $totalSchedules = DB::table('kas_keliling_schedules')->count();
            
            $this->command->info('');
            $this->command->info('📊 Ringkasan Import:');
            $this->command->info("   - Total Area: {$totalAreas}");
            $this->command->info("   - Total Jadwal: {$totalSchedules}");
            $this->command->info('');
            $this->command->info('🎉 Import data Kas Keliling selesai!');

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error('❌ Error: ' . $e->getMessage());
            $this->command->error('   Line: ' . $e->getLine());
            throw $e;
        }
    }

    /**
     * Parse SQL file and remove comments
     */
    private function parseSqlFile(string $sql): array
    {
        // Remove single line comments
        $sql = preg_replace('/^--.*$/m', '', $sql);
        
        // Remove multi-line comments
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Split by semicolon but keep it
        $statements = explode(';', $sql);
        
        // Clean up statements
        $statements = array_map('trim', $statements);
        $statements = array_filter($statements);
        
        return $statements;
    }
}
