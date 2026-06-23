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
        if (!Schema::hasTable('why_choose_us_settings')) {
            Schema::create('why_choose_us_settings', function (Blueprint $table) {
                $table->id();
                $table->string('section_title')->default('Mengapa Memilih Kami');
                $table->text('section_subtitle')->nullable();
                $table->string('section_image')->nullable(); // Main section image
                $table->string('badge_text')->nullable(); // e.g., "100% Syariah Compliant"
                $table->string('badge_icon')->nullable(); // Badge icon
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('why_choose_us_settings');
    }
};
