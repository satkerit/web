<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('posting_mode', ['auto', 'manual'])->default('auto')->after('is_published');
            $table->timestamp('posted_at')->nullable()->after('posting_mode');
            $table->timestamp('scheduled_at')->nullable()->after('posted_at');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['posting_mode', 'posted_at', 'scheduled_at']);
        });
    }
};
