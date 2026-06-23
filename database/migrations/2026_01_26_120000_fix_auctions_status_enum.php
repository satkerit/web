<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Only run for MySQL/MariaDB - SQLite doesn't support ENUM modification
        if (DB::connection()->getDriverName() === 'mysql') {
            try {
                // First, update any invalid status values to a valid one
                DB::table('auctions')
                    ->whereNotIn('status', ['upcoming', 'ongoing', 'closed', 'sold', 'cancelled'])
                    ->update(['status' => 'upcoming']);
                
                // Then modify the column
                DB::statement("ALTER TABLE auctions MODIFY COLUMN status ENUM('upcoming', 'ongoing', 'closed', 'sold', 'cancelled') NOT NULL DEFAULT 'upcoming'");
            } catch (\Exception $e) {
                // If it fails, log it but don't block the migration
                \Illuminate\Support\Facades\Log::warning('Failed to update auctions status enum: ' . $e->getMessage());
            }
        }
        // For SQLite (testing), the status column is already a string, so no modification needed
    }

    public function down(): void
    {
        // Only run for MySQL/MariaDB
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE auctions MODIFY COLUMN status ENUM('upcoming', 'ongoing', 'closed') NOT NULL DEFAULT 'upcoming'");
        }
    }
};
