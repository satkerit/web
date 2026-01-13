<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('timezone')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('isp')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('platform')->nullable(); // Windows, Mac, Linux, Android, iOS
            $table->string('platform_version')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamps();

            $table->index('ip_address');
            $table->index('country');
            $table->index('created_at');
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
