<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kas_keliling_schedules', function (Blueprint $table) {
            $table->string('pic_name')->nullable()->after('notes');
            $table->string('pic_phone')->nullable()->after('pic_name');
            $table->text('facility')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('kas_keliling_schedules', function (Blueprint $table) {
            $table->dropColumn(['pic_name', 'pic_phone', 'facility']);
        });
    }
};
