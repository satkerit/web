<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE auctions MODIFY COLUMN status ENUM(
                'draft', 'published', 'registration_open', 'registration_closed',
                'auction_scheduled', 'auction_ongoing', 'auction_completed',
                'sold', 'unsold', 'cancelled', 'postponed',
                'upcoming', 'ongoing', 'closed'
            ) NOT NULL DEFAULT 'draft'");
        }
    }

    public function down(): void
    {
        // Revert to original list if needed, but adding values is usually safe.
        // We can't easily remove values if data exists.
    }
};
