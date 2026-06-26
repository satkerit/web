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
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'report_keuangan_publikasi_title')) {
                $table->string('report_keuangan_publikasi_title')->default('Laporan Keuangan Publikasi');
            }
            if (!Schema::hasColumn('site_settings', 'report_keuangan_publikasi_subtitle')) {
                $table->string('report_keuangan_publikasi_subtitle')->default('Laporan keuangan publikasi BPR Syariah');
            }

            if (!Schema::hasColumn('site_settings', 'report_tata_kelola_title')) {
                $table->string('report_tata_kelola_title')->default('Laporan Tata Kelola');
            }
            if (!Schema::hasColumn('site_settings', 'report_tata_kelola_subtitle')) {
                $table->string('report_tata_kelola_subtitle')->default('Laporan tata kelola perusahaan');
            }

            if (!Schema::hasColumn('site_settings', 'report_tahunan_title')) {
                $table->string('report_tahunan_title')->default('Laporan Tahunan');
            }
            if (!Schema::hasColumn('site_settings', 'report_tahunan_subtitle')) {
                $table->string('report_tahunan_subtitle')->default('Laporan tahunan BPR Syariah');
            }

            if (!Schema::hasColumn('site_settings', 'report_tahunan_berkelanjutan_title')) {
                $table->string('report_tahunan_berkelanjutan_title')->default('Laporan Tahunan Berkelanjutan');
            }
            if (!Schema::hasColumn('site_settings', 'report_tahunan_berkelanjutan_subtitle')) {
                $table->string('report_tahunan_berkelanjutan_subtitle')->default('Laporan tahunan berkelanjutan BPR Syariah');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'report_keuangan_publikasi_title',
                'report_keuangan_publikasi_subtitle',
                'report_tata_kelola_title',
                'report_tata_kelola_subtitle',
                'report_tahunan_title',
                'report_tahunan_subtitle',
                'report_tahunan_berkelanjutan_title',
                'report_tahunan_berkelanjutan_subtitle',
            ]);
        });
    }
};
