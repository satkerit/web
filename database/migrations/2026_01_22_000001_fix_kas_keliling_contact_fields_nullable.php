<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('kas_keliling')) {
            Schema::table('kas_keliling', function (Blueprint $table) {
                if (Schema::hasColumn('kas_keliling', 'contact_person')) {
                    $table->string('contact_person')->nullable()->change();
                }
                if (Schema::hasColumn('kas_keliling', 'contact_phone')) {
                    $table->string('contact_phone')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_keliling', function (Blueprint $table) {
            $table->string('contact_person')->nullable(false)->change();
            $table->string('contact_phone')->nullable(false)->change();
        });
    }
};
