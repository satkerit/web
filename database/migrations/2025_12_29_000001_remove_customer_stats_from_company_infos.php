<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn([
                'stat_active_customers',
                'stat_customer_satisfaction'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->string('stat_active_customers')->nullable()->after('stat_years_experience');
            $table->integer('stat_customer_satisfaction')->nullable()->after('stat_branch_offices');
        });
    }
};
