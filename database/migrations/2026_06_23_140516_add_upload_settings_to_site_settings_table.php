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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('upload_max_filesize')->default('100M');
            $table->string('post_max_size')->default('100M');
            $table->integer('max_execution_time')->default(300);
            $table->integer('max_input_time')->default(300);
            $table->string('memory_limit')->default('512M');
            $table->integer('max_file_uploads')->default(20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'upload_max_filesize',
                'post_max_size',
                'max_execution_time',
                'max_input_time',
                'memory_limit',
                'max_file_uploads',
            ]);
        });
    }
};
