<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // unique identifier like 'dashboard', 'news', etc.
            $table->string('name'); // display name
            $table->string('route')->nullable(); // route name
            $table->string('icon')->nullable(); // SVG icon or icon class
            $table->string('section')->nullable(); // section/group name
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('admin_menu_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_menu_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('role_id');
            $table->boolean('can_access')->default(true);
            $table->timestamps();

            $table->unique(['admin_menu_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_menu_permissions');
        Schema::dropIfExists('admin_menus');
    }
};
