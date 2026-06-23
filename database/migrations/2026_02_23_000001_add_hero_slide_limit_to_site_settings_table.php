<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'hero_slide_limit')) {
                $table->integer('hero_slide_limit')->default(5)->after('hero_slider_delay')->comment('Maksimal jumlah slide hero yang ditampilkan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('hero_slide_limit');
        });
    }
};