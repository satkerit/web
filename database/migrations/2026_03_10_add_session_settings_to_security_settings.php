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
        Schema::table('security_settings', function (Blueprint $table) {
            // Session Management Settings
            $table->integer('session_lifetime')->default(120)->after('log_security_events')->comment('Session lifetime in minutes');
            $table->integer('idle_timeout')->default(30)->after('session_lifetime')->comment('Idle timeout in minutes for admin');
            $table->integer('idle_warning')->default(5)->after('idle_timeout')->comment('Idle warning in minutes before logout');
            $table->boolean('auto_extend_session')->default(true)->after('idle_warning')->comment('Auto extend session on user activity');
            $table->boolean('enable_session_tracking')->default(true)->after('auto_extend_session')->comment('Enable session activity tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_settings', function (Blueprint $table) {
            $table->dropColumn([
                'session_lifetime',
                'idle_timeout',
                'idle_warning',
                'auto_extend_session',
                'enable_session_tracking',
            ]);
        });
    }
};
