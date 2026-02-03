<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;

return new class extends Migration
{
    public function up(): void
    {
        // Check if menu already exists
        if (AdminMenu::where('key', 'why-choose-us')->exists()) {
            return;
        }

        // Add menu item
        $menu = AdminMenu::create([
            'key' => 'why-choose-us',
            'name' => 'Keunggulan',
            'route' => 'admin.why-choose-us.index',
            'icon' => 'why-choose-us', // Matches key in menu.blade.php
            'section' => 'Konten',
            'order' => 25, // Hero slides usually 20, news 30
            'is_active' => true,
        ]);

        // Permissions will be handled by AdminMenuSeeder

        // Clear cache
        AdminMenu::clearCache();
    }

    public function down(): void
    {
        $menu = AdminMenu::where('key', 'why-choose-us')->first();
        if ($menu) {
            $menu->permissions()->delete();
            $menu->delete();
        }
        AdminMenu::clearCache();
    }
};
