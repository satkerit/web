<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL, kita perlu mengubah enum dengan cara yang berbeda
        DB::statement("ALTER TABLE auctions MODIFY COLUMN status ENUM('upcoming', 'ongoing', 'closed', 'sold', 'cancelled') NOT NULL DEFAULT 'upcoming'");
    }

    public function down(): void
    {
        // Kembalikan ke enum lama (hati-hati, data 'sold' dan 'cancelled' akan hilang)
        DB::statement("ALTER TABLE auctions MODIFY COLUMN status ENUM('upcoming', 'ongoing', 'closed') NOT NULL DEFAULT 'upcoming'");
    }
};
