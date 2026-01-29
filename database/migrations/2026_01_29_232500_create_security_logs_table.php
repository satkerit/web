<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('security_logs')) {
            Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->string('user_agent', 500)->nullable();
            $table->string('request_method', 10);
            $table->string('request_url', 2048);
            $table->text('payload')->nullable(); // Sanitized input data
            $table->string('threat_type', 50)->index(); // sql_injection, xss, path_traversal, etc.
            $table->string('threat_level', 20)->default('medium'); // low, medium, high, critical
            $table->text('matched_pattern')->nullable(); // The pattern that was matched
            $table->text('raw_input')->nullable(); // The input that triggered the detection
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('country_code', 5)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->boolean('was_blocked')->default(false);
            $table->timestamps();

            // Indexes for efficient querying
            $table->index('created_at');
            $table->index(['ip_address', 'created_at']);
        });
        }

        // Add attack_count tracking to blocked_ips if not exists
        if (!Schema::hasColumn('blocked_ips', 'attack_count')) {
            Schema::table('blocked_ips', function (Blueprint $table) {
                $table->integer('attack_count')->default(0)->after('attempts');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('security_logs');

        if (Schema::hasColumn('blocked_ips', 'attack_count')) {
            Schema::table('blocked_ips', function (Blueprint $table) {
                $table->dropColumn('attack_count');
            });
        }
    }
};
