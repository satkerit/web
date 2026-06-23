<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('company_infos', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('organization_structure');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn('profile_image');
        });
    }
};
