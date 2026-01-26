<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('auction_number')->unique(); // Nomor Lelang
            $table->string('object_number')->nullable(); // Nomor Objek
            
            // Asset Information
            $table->enum('asset_type', [
                'tanah', 'rumah', 'ruko', 'apartemen', 'gedung', 
                'pabrik', 'kendaraan', 'mesin', 'lainnya'
            ]);
            $table->string('asset_category')->nullable(); // Kategori lebih spesifik
            $table->text('asset_description')->nullable(); // Deskripsi aset detail
            
            // Certificate Information
            $table->enum('certificate_type', [
                'SHM', 'SHGB', 'SHP', 'AJB', 'PPJB', 'Girik', 'BPKB', 'Lainnya'
            ])->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('certificate_date')->nullable();
            $table->string('certificate_issued_by')->nullable(); // Diterbitkan oleh
            
            // Property Details
            $table->decimal('land_area', 10, 2)->nullable(); // Luas tanah (m²)
            $table->decimal('building_area', 10, 2)->nullable(); // Luas bangunan (m²)
            $table->string('building_condition')->nullable(); // Kondisi bangunan
            $table->integer('floors')->nullable(); // Jumlah lantai
            $table->integer('bedrooms')->nullable(); // Kamar tidur
            $table->integer('bathrooms')->nullable(); // Kamar mandi
            $table->integer('parking_spaces')->nullable(); // Tempat parkir
            $table->year('year_built')->nullable(); // Tahun dibangun
            
            // Location Details
            $table->text('address'); // Alamat lengkap
            $table->string('village')->nullable(); // Kelurahan/Desa
            $table->string('district')->nullable(); // Kecamatan
            $table->string('city')->nullable(); // Kota/Kabupaten
            $table->string('province')->nullable(); // Provinsi
            $table->string('postal_code')->nullable(); // Kode pos
            $table->decimal('latitude', 10, 8)->nullable(); // Koordinat
            $table->decimal('longitude', 11, 8)->nullable(); // Koordinat
            
            // Debtor Information
            $table->string('debtor_name')->nullable();
            $table->string('debtor_id_number')->nullable(); // NIK/No. Identitas
            $table->text('debtor_address')->nullable();
            
            // Auction Information
            $table->enum('auction_type', [
                'eksekusi_hak_tanggungan',
                'eksekusi_fidusia', 
                'eksekusi_hipotik',
                'non_eksekusi_wajib',
                'non_eksekusi_sukarela'
            ]);
            $table->string('auction_method')->default('lelang_terbuka'); // Metode lelang
            $table->datetime('auction_date'); // Tanggal pelaksanaan
            $table->time('auction_time')->nullable(); // Waktu pelaksanaan
            $table->string('auction_location'); // Tempat pelaksanaan
            $table->text('auction_address')->nullable(); // Alamat tempat lelang
            
            // Registration
            $table->datetime('registration_start')->nullable(); // Mulai pendaftaran
            $table->datetime('registration_end')->nullable(); // Akhir pendaftaran
            $table->text('registration_requirements')->nullable(); // Syarat pendaftaran
            $table->text('registration_procedure')->nullable(); // Tata cara pendaftaran
            
            // Pricing
            $table->decimal('limit_price', 15, 2); // Harga limit
            $table->decimal('estimated_price', 15, 2)->nullable(); // Nilai taksiran
            $table->decimal('deposit_amount', 15, 2)->nullable(); // Uang jaminan
            $table->decimal('deposit_percentage', 5, 2)->default(20); // Persentase jaminan
            $table->decimal('increment_amount', 15, 2)->nullable(); // Kelipatan penawaran
            
            // Bank Information
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('swift_code')->nullable();
            
            // Legal Information
            $table->string('creditor_name')->nullable(); // Nama kreditur
            $table->text('creditor_address')->nullable(); // Alamat kreditur
            $table->string('legal_basis')->nullable(); // Dasar hukum
            $table->string('court_decision')->nullable(); // Putusan pengadilan
            $table->date('court_decision_date')->nullable();
            $table->decimal('debt_amount', 15, 2)->nullable(); // Jumlah hutang
            $table->text('encumbrance_details')->nullable(); // Rincian beban
            
            // Viewing Information
            $table->datetime('viewing_start')->nullable(); // Mulai viewing
            $table->datetime('viewing_end')->nullable(); // Akhir viewing
            $table->text('viewing_schedule')->nullable(); // Jadwal viewing
            $table->text('viewing_contact')->nullable(); // Kontak viewing
            $table->text('viewing_notes')->nullable(); // Catatan viewing
            
            // Terms & Conditions
            $table->text('terms_conditions')->nullable();
            $table->text('special_conditions')->nullable(); // Syarat khusus
            $table->text('payment_terms')->nullable(); // Syarat pembayaran
            $table->integer('payment_deadline_days')->default(30); // Batas waktu pelunasan (hari)
            $table->text('delivery_terms')->nullable(); // Syarat penyerahan
            
            // Organizer Information
            $table->string('organizer_name')->nullable(); // Penyelenggara
            $table->string('organizer_type')->nullable(); // Jenis penyelenggara (KPKNL, Balai Lelang, dll)
            $table->text('organizer_address')->nullable();
            $table->string('organizer_phone')->nullable();
            $table->string('organizer_email')->nullable();
            $table->string('organizer_website')->nullable();
            
            // Contact Information
            $table->string('contact_person');
            $table->string('contact_position')->nullable(); // Jabatan
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->text('contact_office_hours')->nullable(); // Jam kerja
            
            // Documents & Media
            $table->json('images')->nullable(); // Foto-foto
            $table->json('documents')->nullable(); // Dokumen-dokumen
            $table->json('floor_plans')->nullable(); // Denah
            $table->json('certificates')->nullable(); // Sertifikat
            $table->string('virtual_tour_url')->nullable(); // Virtual tour
            $table->string('video_url')->nullable(); // Video
            
            // Status & Results
            $table->enum('status', [
                'draft', 'published', 'registration_open', 'registration_closed',
                'auction_scheduled', 'auction_ongoing', 'auction_completed',
                'sold', 'unsold', 'cancelled', 'postponed'
            ])->default('draft');
            $table->text('status_notes')->nullable(); // Catatan status
            
            // Auction Results
            $table->decimal('winning_bid', 15, 2)->nullable();
            $table->string('winner_name')->nullable();
            $table->string('winner_id_number')->nullable();
            $table->text('winner_address')->nullable();
            $table->string('winner_phone')->nullable();
            $table->datetime('sold_at')->nullable();
            $table->text('auction_notes')->nullable(); // Catatan hasil lelang
            $table->integer('total_bidders')->nullable(); // Jumlah peserta
            $table->integer('total_bids')->nullable(); // Jumlah penawaran
            
            // Additional Information
            $table->text('facilities')->nullable(); // Fasilitas
            $table->text('nearby_facilities')->nullable(); // Fasilitas sekitar
            $table->text('transportation_access')->nullable(); // Akses transportasi
            $table->text('investment_potential')->nullable(); // Potensi investasi
            $table->text('market_analysis')->nullable(); // Analisis pasar
            $table->text('risk_factors')->nullable(); // Faktor risiko
            
            // SEO & Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // Tracking
            $table->integer('view_count')->default(0);
            $table->integer('interest_count')->default(0); // Jumlah yang berminat
            $table->integer('download_count')->default(0); // Download dokumen
            
            // Publishing
            $table->datetime('published_at')->nullable();
            $table->datetime('featured_until')->nullable(); // Featured sampai
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'published_at']);
            $table->index(['auction_date', 'status']);
            $table->index(['asset_type', 'city']);
            $table->index(['limit_price', 'status']);
            $table->index(['is_featured', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};