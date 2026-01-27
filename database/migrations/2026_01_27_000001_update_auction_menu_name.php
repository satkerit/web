<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenu;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update auction menu name from "Lelang" to "Lelang Agunan"
        AdminMenu::where('key', 'auctions')
                 ->where('name', 'Lelang')
                 ->update(['name' => 'Lelang Agunan']);

        // Clear menu cache if method exists
        if (method_exists(AdminMenu::class, 'clearCache')) {
            AdminMenu::clearCache();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Update auction menu name back to "Lelang"
        AdminMenu::where('key', 'auctions')
                 ->where('name', 'Lelang Agunan')
                 ->update(['name' => 'Lelang']);

        // Clear menu cache if method exists
        if (method_exists(AdminMenu::class, 'clearCache')) {
            AdminMenu::clearCache();
        }
    }
};