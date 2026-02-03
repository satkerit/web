<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('admin_menu_permissions', 'role')) {
            return;
        }

        // Drop foreign key constraint first
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->dropForeign(['admin_menu_id']);
        });

        // Remove unique constraint, then remove role string column from admin_menu_permissions
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->dropUnique(['admin_menu_id', 'role']);
        });

        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Make role_id required and add new unique constraint
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable(false)->change();
            $table->unique(['admin_menu_id', 'role_id']);
        });

        // Re-add foreign key constraint
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->foreign('admin_menu_id')->references('id')->on('admin_menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Drop foreign key constraint first
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->dropForeign(['admin_menu_id']);
        });

        // Drop unique constraint and add back role string column to admin_menu_permissions
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->dropUnique(['admin_menu_id', 'role_id']);
            $table->string('role')->after('admin_menu_id');
        });

        // Sync role strings from role_id for admin_menu_permissions
        $permissions = \App\Models\AdminMenuPermission::with('role')->get();
        foreach ($permissions as $permission) {
            if ($permission->role) {
                $permission->update(['role' => $permission->role->name]);
            }
        }

        // Add back original unique constraint
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->unique(['admin_menu_id', 'role']);
        });

        // Re-add foreign key constraint
        Schema::table('admin_menu_permissions', function (Blueprint $table) {
            $table->foreign('admin_menu_id')->references('id')->on('admin_menus')->onDelete('cascade');
        });
    }
};