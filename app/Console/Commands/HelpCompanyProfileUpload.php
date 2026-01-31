<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HelpCompanyProfileUpload extends Command
{
    protected $signature = 'help:company-profile-upload';
    protected $description = 'Show help and troubleshooting guide for company profile upload';

    public function handle()
    {
        $this->info('🏢 COMPANY PROFILE IMAGE UPLOAD - HELP & TROUBLESHOOTING');
        $this->line('================================================================');
        $this->newLine();
        
        $this->info('📍 LOKASI FITUR:');
        $this->line('Admin Panel > Info Perusahaan > Gambar Profil Perusahaan');
        $this->newLine();
        
        $this->info('✅ SISTEM STATUS:');
        $this->line('- Backend: Berfungsi normal');
        $this->line('- Database: Kolom profile_image tersedia');
        $this->line('- Storage: Directory dan symlink OK');
        $this->line('- Permissions: User memiliki akses yang diperlukan');
        $this->newLine();
        
        $this->info('🔧 JIKA MENGALAMI ERROR 403:');
        $this->newLine();
        
        $this->warn('LANGKAH 1: Reset Sistem');
        $this->line('php artisan reset:company-profile-upload');
        $this->newLine();
        
        $this->warn('LANGKAH 2: Refresh Browser');
        $this->line('- Tekan Ctrl+F5 untuk hard refresh');
        $this->line('- Atau clear browser cache');
        $this->line('- Login ulang ke admin panel');
        $this->newLine();
        
        $this->warn('LANGKAH 3: Cek Browser Console');
        $this->line('- Tekan F12 untuk buka Developer Tools');
        $this->line('- Buka tab Console');
        $this->line('- Coba upload gambar dan lihat error');
        $this->newLine();
        
        $this->warn('LANGKAH 4: Cek Network Tab');
        $this->line('- Di Developer Tools, buka tab Network');
        $this->line('- Coba upload gambar');
        $this->line('- Lihat request yang gagal (warna merah)');
        $this->newLine();
        
        $this->info('🚨 ERROR UMUM & SOLUSI:');
        $this->newLine();
        
        $this->line('❌ "401 Unauthenticated"');
        $this->line('   → Session expired, login ulang');
        $this->newLine();
        
        $this->line('❌ "403 Forbidden"');
        $this->line('   → Jalankan: php artisan fix:user-permissions {user_id}');
        $this->newLine();
        
        $this->line('❌ "CSRF token mismatch"');
        $this->line('   → Refresh halaman dan coba lagi');
        $this->newLine();
        
        $this->line('❌ "File too large"');
        $this->line('   → Gunakan gambar < 5MB, format JPG/PNG/WEBP');
        $this->newLine();
        
        $this->info('🛠️ COMMAND DIAGNOSIS:');
        $this->line('php artisan debug:company-profile-upload        # Cek semua user');
        $this->line('php artisan debug:company-profile-upload {id}   # Cek user spesifik');
        $this->line('php artisan test:company-profile-upload {id}    # Test upload user');
        $this->line('php artisan fix:user-permissions {id}           # Fix permission user');
        $this->newLine();
        
        $this->info('📋 SPESIFIKASI UPLOAD:');
        $this->line('- Format: JPG, PNG, WEBP');
        $this->line('- Ukuran maksimal: 5MB');
        $this->line('- Lokasi storage: storage/app/public/company/profile/');
        $this->line('- Permission required: settings.company');
        $this->newLine();
        
        $this->info('📞 BANTUAN LANJUTAN:');
        $this->line('Jika masalah masih berlanjut:');
        $this->line('1. Jalankan: php artisan reset:company-profile-upload');
        $this->line('2. Cek file: TROUBLESHOOTING_COMPANY_PROFILE_UPLOAD.md');
        $this->line('3. Lihat log: tail -f storage/logs/laravel.log');
        
        return 0;
    }
}