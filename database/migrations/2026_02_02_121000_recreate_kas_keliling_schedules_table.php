<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('kas_keliling_schedules');

        Schema::create('kas_keliling_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kas_keliling_id')->constrained('kas_keliling')->onDelete('cascade');
            $table->date('schedule_date');
            $table->string('day_name')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->json('route')->nullable();
            $table->json('services_offered')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['kas_keliling_id', 'schedule_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_keliling_schedules');
    }
};
