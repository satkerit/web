<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_settings', function (Blueprint $table) {
            $table->id();

            // Notifikasi Email
            $table->string('admin_email')->nullable()->comment('Email penerima notifikasi pengaduan nasabah');
            $table->string('cc_emails')->nullable()->comment('CC email (pisahkan dengan koma)');
            $table->boolean('notify_on_new')->default(true)->comment('Kirim notifikasi saat pengaduan baru masuk');
            $table->boolean('notify_on_status_change')->default(true)->comment('Kirim notifikasi saat status berubah');
            $table->boolean('send_confirmation_to_customer')->default(true)->comment('Kirim konfirmasi ke nasabah');

            // SLA & Batas Waktu
            $table->unsignedInteger('sla_days_low')->default(14)->comment('SLA hari untuk prioritas rendah');
            $table->unsignedInteger('sla_days_medium')->default(7)->comment('SLA hari untuk prioritas sedang');
            $table->unsignedInteger('sla_days_high')->default(3)->comment('SLA hari untuk prioritas tinggi');

            // Pengaturan Form
            $table->boolean('require_account_number')->default(false)->comment('Wajibkan nomor rekening');
            $table->boolean('require_phone')->default(true)->comment('Wajibkan nomor telepon');
            $table->boolean('allow_attachments')->default(true)->comment('Izinkan lampiran file');
            $table->unsignedInteger('max_attachments')->default(5)->comment('Maksimal jumlah lampiran');
            $table->unsignedInteger('max_file_size_mb')->default(5)->comment('Ukuran maksimal file (MB)');
            $table->string('allowed_file_types')->default('pdf,doc,docx,jpg,jpeg,png')->comment('Tipe file yang diizinkan');

            // Pengaturan Tiket
            $table->string('ticket_prefix')->default('ADU')->comment('Prefix nomor tiket');
            $table->boolean('auto_assign_priority')->default(true)->comment('Otomatis tentukan prioritas');

            // Teks & Konten
            $table->text('form_intro_text')->nullable()->comment('Teks pengantar form pengaduan');
            $table->text('success_message')->nullable()->comment('Pesan sukses setelah submit');
            $table->text('terms_text')->nullable()->comment('Teks syarat & ketentuan');

            // Kategori Aktif
            $table->json('active_categories')->nullable()->comment('Kategori pengaduan yang aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_settings');
    }
};
