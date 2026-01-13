<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->text('maintenance_allowed_ips')->nullable();
            $table->timestamp('maintenance_end_time')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            'maintenance_mode' => false,
            'maintenance_message' => 'Website sedang dalam pemeliharaan untuk peningkatan layanan. Silakan kembali beberapa saat lagi.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
