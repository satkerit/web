<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->decimal('limit_price', 15, 2)->nullable()->change();
            $table->datetime('auction_date')->nullable()->change();
            $table->string('contact_person')->nullable()->change();
            $table->string('contact_phone')->nullable()->change();
            
            if (!Schema::hasColumn('auctions', 'contacts')) {
                $table->json('contacts')->nullable()->after('contact_office_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // We keep them nullable in down() to prevent data loss or errors if nulls exist
            if (Schema::hasColumn('auctions', 'contacts')) {
                $table->dropColumn('contacts');
            }
        });
    }
};
