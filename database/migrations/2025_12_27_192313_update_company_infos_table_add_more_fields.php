<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->string('favicon')->nullable()->after('logo');
            $table->string('tagline')->nullable()->after('name');
            $table->string('fax')->nullable()->after('phone');
            $table->string('whatsapp')->nullable()->after('fax');

            // Social Media
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('tiktok')->nullable();

            // Regulatory Info
            $table->string('ojk_license')->nullable();
            $table->text('ojk_tagline')->nullable();
            $table->text('lps_tagline')->nullable();
            $table->string('lps_guarantee_amount')->nullable();

            // Additional Info
            $table->text('footer_description')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->json('operational_hours')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn([
                'favicon',
                'tagline',
                'fax',
                'whatsapp',
                'facebook',
                'instagram',
                'twitter',
                'youtube',
                'linkedin',
                'tiktok',
                'ojk_license',
                'ojk_tagline',
                'lps_tagline',
                'lps_guarantee_amount',
                'footer_description',
                'meta_description',
                'meta_keywords',
                'operational_hours'
            ]);
        });
    }
};
