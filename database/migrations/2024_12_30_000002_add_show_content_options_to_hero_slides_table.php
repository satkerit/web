<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->boolean('show_title')->default(true)->after('transition_duration');
            $table->boolean('show_subtitle')->default(true)->after('show_title');
            $table->boolean('show_button')->default(true)->after('show_subtitle');
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['show_title', 'show_subtitle', 'show_button']);
        });
    }
};
