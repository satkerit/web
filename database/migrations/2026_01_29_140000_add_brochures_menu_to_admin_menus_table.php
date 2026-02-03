<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing items to make space
        DB::table('admin_menus')->where('key', 'auctions')->update(['order' => 14]);
        DB::table('admin_menus')->where('key', 'reports')->update(['order' => 15]);

        // Insert Brochure menu
        $exists = DB::table('admin_menus')->where('key', 'brochures')->exists();
        
        if (!$exists) {
            $id = DB::table('admin_menus')->insertGetId([
                'key' => 'brochures',
                'name' => 'Brosur Pembiayaan',
                'route' => 'admin.brochures.index',
                'section' => 'Konten',
                'order' => 13,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $id = DB::table('admin_menus')->where('key', 'brochures')->value('id');
            DB::table('admin_menus')->where('id', $id)->update([
                'name' => 'Brosur Pembiayaan',
                'route' => 'admin.brochures.index',
                'section' => 'Konten',
                'order' => 13,
                'is_active' => true,
                'updated_at' => now(),
            ]);
        }

        // Permissions will be handled by AdminMenuSeeder


        Cache::forget('admin_menus_all_with_permissions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $menu = DB::table('admin_menus')->where('key', 'brochures')->first();
        
        if ($menu) {
            DB::table('admin_menu_permissions')->where('admin_menu_id', $menu->id)->delete();
            DB::table('admin_menus')->where('id', $menu->id)->delete();
        }

        // Revert order
        DB::table('admin_menus')->where('key', 'auctions')->update(['order' => 13]);
        DB::table('admin_menus')->where('key', 'reports')->update(['order' => 14]);
        
        Cache::forget('admin_menus_all_with_permissions');
    }
};
