<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'category')) {
                $table->string('category')->default('Berita')->after('excerpt');
            }
            if (!Schema::hasColumn('news', 'author_id')) {
                $table->foreignId('author_id')->nullable()->after('published_at')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['category', 'author_id']);
        });
    }
};
