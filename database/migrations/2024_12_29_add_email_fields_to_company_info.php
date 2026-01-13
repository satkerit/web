<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->string('email_contact')->nullable()->after('email');
            $table->string('email_complaint')->nullable()->after('email_contact');
            $table->string('email_whistleblowing')->nullable()->after('email_complaint');
        });
    }

    public function down(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn(['email_contact', 'email_complaint', 'email_whistleblowing']);
        });
    }
};
