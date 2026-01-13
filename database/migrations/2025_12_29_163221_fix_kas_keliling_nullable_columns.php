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
        Schema::table('kas_keliling', function (Blueprint $table) {
            $table->json('schedule')->nullable()->default(null)->change();
            $table->json('route')->nullable()->default(null)->change();
            $table->json('services_offered')->nullable()->default(null)->change();
            $table->json('operational_hours')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_keliling', function (Blueprint $table) {
            $table->json('schedule')->nullable(false)->change();
            $table->json('route')->nullable(false)->change();
            $table->json('services_offered')->nullable(false)->change();
            $table->json('operational_hours')->nullable(false)->change();
        });
    }
};
