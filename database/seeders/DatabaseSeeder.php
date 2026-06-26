<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RolePermissionSeeder::class,
            AdminMenuSeeder::class,
            CompanyInfoSeeder::class,
            BoardMemberSeeder::class,
            ProductSeeder::class,
            HeroSlideSeeder::class,
            OfficeSeeder::class,
            AuctionSeeder::class,
            NewsSeeder::class,
            FinancingConfigSeeder::class,
            KasKelilingSeeder::class,
            BrochureSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}
