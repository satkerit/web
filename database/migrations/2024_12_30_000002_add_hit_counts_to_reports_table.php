<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedInteger('preview_count')->default(0)->after('scheduled_at');
            $table->unsignedInteger('download_count')->default(0)->after('preview_count');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['preview_count', 'download_count']);
        });
    }
};
