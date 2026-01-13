<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->string('stat_total_assets')->nullable()->after('stat_branch_offices')->comment('Total aset dalam format string (misal: 150 Miliar)');
            $table->integer('stat_cash_offices')->nullable()->after('stat_total_assets')->comment('Jumlah kantor kas');
            $table->integer('stat_mobile_cash_offices')->nullable()->after('stat_cash_offices')->comment('Jumlah kantor kas keliling');
        });
    }

    public function down(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn([
                'stat_total_assets',
                'stat_cash_offices',
                'stat_mobile_cash_offices'
            ]);
        });
    }
};
