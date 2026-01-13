<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();

            // Rate Limiting
            $table->integer('rate_limit_web')->default(120);
            $table->integer('rate_limit_admin')->default(100);
            $table->integer('rate_limit_login')->default(5);
            $table->integer('rate_limit_password_reset')->default(3);
            $table->integer('rate_limit_download')->default(30);

            // IP Blocking
            $table->integer('block_threshold')->default(10);
            $table->integer('block_duration_hours')->default(24);
            $table->text('ip_whitelist')->nullable();
            $table->text('ip_blacklist')->nullable();

            // Security Features
            $table->boolean('enable_suspicious_blocking')->default(true);
            $table->boolean('enable_rate_limiting')->default(true);
            $table->boolean('log_security_events')->default(true);

            $table->timestamps();
        });

        // Create blocked IPs table
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->string('reason')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamp('blocked_until')->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->timestamps();

            $table->index('ip_address');
            $table->index('blocked_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('security_settings');
    }
};
