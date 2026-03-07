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
        Schema::table('financing_configs', function (Blueprint $table) {
            // Add calculation_type field: 'margin' or 'profit_sharing'
            $table->string('calculation_type', 20)->default('margin')->after('type');
            
            // Add description field for additional notes
            $table->text('description')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financing_configs', function (Blueprint $table) {
            $table->dropColumn(['calculation_type', 'description']);
        });
    }
};
