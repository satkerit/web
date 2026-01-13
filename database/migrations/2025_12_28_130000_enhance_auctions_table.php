<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Informasi Objek Lelang
            $table->string('object_number')->nullable()->after('slug'); // No. Objek Lelang
            $table->string('certificate_type')->nullable()->after('asset_type'); // Jenis Sertifikat (SHM, SHGB, AJB, dll)
            $table->string('certificate_number')->nullable()->after('certificate_type'); // No. Sertifikat
            $table->decimal('land_area', 15, 2)->nullable()->after('certificate_number'); // Luas Tanah (m²)
            $table->decimal('building_area', 15, 2)->nullable()->after('land_area'); // Luas Bangunan (m²)

            // Informasi Debitur (opsional, untuk transparansi)
            $table->string('debtor_name')->nullable()->after('building_area'); // Nama Debitur (bisa disamarkan)

            // Informasi Lelang
            $table->string('auction_type')->default('eksekusi')->after('status'); // Jenis Lelang: eksekusi, sukarela, non-eksekusi
            $table->string('auction_location')->nullable()->after('auction_type'); // Tempat Pelaksanaan Lelang
            $table->decimal('deposit_amount', 15, 2)->nullable()->after('auction_location'); // Uang Jaminan
            $table->decimal('deposit_percentage', 5, 2)->nullable()->after('deposit_amount'); // Persentase Uang Jaminan
            $table->string('bank_account')->nullable()->after('deposit_percentage'); // No. Rekening untuk Deposit
            $table->string('bank_name')->nullable()->after('bank_account'); // Nama Bank
            $table->string('account_holder')->nullable()->after('bank_name'); // Atas Nama Rekening

            // Informasi Tambahan
            $table->text('terms_conditions')->nullable()->after('account_holder'); // Syarat & Ketentuan
            $table->text('viewing_schedule')->nullable()->after('terms_conditions'); // Jadwal Open House/Viewing
            $table->string('kpknl_office')->nullable()->after('viewing_schedule'); // Kantor KPKNL (jika melalui KPKNL)
            $table->string('risalah_number')->nullable()->after('kpknl_office'); // No. Risalah Lelang

            // Hasil Lelang
            $table->decimal('winning_bid', 15, 2)->nullable()->after('risalah_number'); // Harga Terjual
            $table->string('winner_name')->nullable()->after('winning_bid'); // Nama Pemenang
            $table->datetime('sold_at')->nullable()->after('winner_name'); // Tanggal Terjual

            // SEO & Meta
            $table->text('meta_description')->nullable()->after('sold_at');
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn([
                'object_number',
                'certificate_type',
                'certificate_number',
                'land_area',
                'building_area',
                'debtor_name',
                'auction_type',
                'auction_location',
                'deposit_amount',
                'deposit_percentage',
                'bank_account',
                'bank_name',
                'account_holder',
                'terms_conditions',
                'viewing_schedule',
                'kpknl_office',
                'risalah_number',
                'winning_bid',
                'winner_name',
                'sold_at',
                'meta_description'
            ]);
        });
    }
};
