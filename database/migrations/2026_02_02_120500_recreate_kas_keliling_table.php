<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kas_keliling')) {
            Schema::create('kas_keliling', function (Blueprint $table) {
                $table->id();
                $table->string('area_name');
                $table->date('schedule_date')->nullable();
                $table->string('day_name')->nullable();
                $table->json('schedule')->nullable();
                $table->json('route')->nullable();
                $table->string('contact_person')->nullable();
                $table->string('contact_phone')->nullable();
                $table->json('services_offered')->nullable();
                $table->json('operational_hours')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_keliling');
    }
};
