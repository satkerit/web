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
        if (Schema::hasTable('company_infos')) {
            return;
        }

        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name')->comment('Nama perusahaan');
            $table->string('tagline')->nullable()->comment('Tagline perusahaan');
            $table->text('description')->nullable()->comment('Deskripsi perusahaan');
            $table->integer('established_year')->nullable()->comment('Tahun berdiri');

            // Contact Information
            $table->text('address')->nullable()->comment('Alamat lengkap');
            $table->string('phone', 50)->nullable()->comment('Nomor telepon');
            $table->string('fax', 50)->nullable()->comment('Nomor fax');
            $table->string('whatsapp', 50)->nullable()->comment('Nomor WhatsApp');
            $table->string('email')->nullable()->comment('Email utama');
            $table->string('email_contact')->nullable()->comment('Email kontak');
            $table->string('email_complaint')->nullable()->comment('Email pengaduan');
            $table->string('email_whistleblowing')->nullable()->comment('Email whistleblowing');
            $table->string('website')->nullable()->comment('Website URL');

            // Visual Assets
            $table->string('logo')->nullable()->comment('Logo utama');
            $table->string('logo_footer')->nullable()->comment('Logo untuk footer');
            $table->boolean('logo_footer_remove_bg')->default(false)->comment('Remove background logo footer');
            $table->integer('logo_footer_opacity')->default(100)->comment('Opacity logo footer (0-100)');
            $table->string('favicon')->nullable()->comment('Favicon');
            $table->string('profile_image')->nullable()->comment('Gambar profil perusahaan');
            $table->string('organization_structure')->nullable()->comment('Struktur organisasi');

            // Company Profile
            $table->text('vision')->nullable()->comment('Visi perusahaan');
            $table->text('mission')->nullable()->comment('Misi perusahaan');
            $table->text('history')->nullable()->comment('Sejarah perusahaan');

            // Statistics
            $table->integer('stat_years_experience')->nullable()->comment('Tahun pengalaman');
            $table->integer('stat_branch_offices')->nullable()->comment('Jumlah kantor cabang');
            $table->string('stat_total_assets')->nullable()->comment('Total aset');
            $table->integer('stat_cash_offices')->nullable()->comment('Jumlah kantor kas');
            $table->integer('stat_mobile_cash_offices')->nullable()->comment('Jumlah kas keliling');
            $table->unsignedBigInteger('legacy_visitor_count')->default(0)->comment('Jumlah pengunjung legacy');

            // Social Media
            $table->string('facebook')->nullable()->comment('Facebook URL');
            $table->string('instagram')->nullable()->comment('Instagram URL');
            $table->string('twitter')->nullable()->comment('Twitter URL');
            $table->string('youtube')->nullable()->comment('YouTube URL');
            $table->string('linkedin')->nullable()->comment('LinkedIn URL');
            $table->string('tiktok')->nullable()->comment('TikTok URL');

            // Regulatory Information
            $table->string('ojk_license')->nullable()->comment('Nomor izin OJK');
            $table->text('ojk_tagline')->nullable()->comment('Tagline OJK');
            $table->text('lps_tagline')->nullable()->comment('Tagline LPS');
            $table->string('lps_guarantee_amount')->nullable()->comment('Jumlah jaminan LPS');

            // SEO & Footer
            $table->text('footer_description')->nullable()->comment('Deskripsi footer');
            $table->string('meta_description')->nullable()->comment('Meta description');
            $table->text('meta_keywords')->nullable()->comment('Meta keywords');

            // Operational Hours (JSON)
            $table->json('operational_hours')->nullable()->comment('Jam operasional dalam format JSON');

            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('established_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_infos');
    }
};
