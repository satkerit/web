<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financing_configs', function (Blueprint $table) {
            $table->boolean('dp_enabled')->default(false)->after('available_tenors');
            $table->decimal('dp_min_percentage', 5, 2)->nullable()->after('dp_enabled');
            $table->decimal('dp_max_percentage', 5, 2)->nullable()->after('dp_min_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('financing_configs', function (Blueprint $table) {
            $table->dropColumn(['dp_enabled', 'dp_min_percentage', 'dp_max_percentage']);
        });
    }
};
