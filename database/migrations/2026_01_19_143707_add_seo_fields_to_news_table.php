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
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('news', 'tags')) {
                $table->string('tags')->nullable()->after('meta_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['meta_description', 'tags']);
        });
    }
};
