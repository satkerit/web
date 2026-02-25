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
        Schema::table('company_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('company_infos', 'logo_footer_remove_bg')) {
                $table->boolean('logo_footer_remove_bg')->default(false)->after('logo_footer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn('logo_footer_remove_bg');
        });
    }
};
