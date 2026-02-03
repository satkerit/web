<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('permissions', 'is_system')) {
                    $table->boolean('is_system')->default(false);
                }
                if (!Schema::hasColumn('permissions', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        } else {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->text('description')->nullable();
                $table->string('group')->default('General');
                $table->boolean('is_system')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['group', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
