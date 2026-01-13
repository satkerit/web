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
            if (!Schema::hasColumn('news', 'category')) {
                $table->string('category', 100)->nullable()->after('featured_image');
            }
            if (!Schema::hasColumn('news', 'author_id')) {
                $table->foreignId('author_id')->nullable()->after('author')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('news', 'author_id')) {
                $table->dropForeign(['author_id']);
                $table->dropColumn('author_id');
            }
        });
    }
};
