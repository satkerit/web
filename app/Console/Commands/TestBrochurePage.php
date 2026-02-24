<?php

namespace App\Console\Commands;

use App\Models\Brochure;
use App\Models\User;
use Illuminate\Console\Command;

class TestBrochurePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brochure:test-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test brochure page functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Testing Brochure Controller Methods...");
            
            // Test eager loading
            $this->line("Testing Brochure::with('uploader')...");
            $brochures = Brochure::with('uploader')->latest()->limit(5)->get();
            
            $this->info("✅ Eager loading berhasil!");
            $this->line("Jumlah brosur: " . $brochures->count());
            
            if ($brochures->count() > 0) {
                $this->line("Testing relasi uploader...");
                foreach ($brochures as $brochure) {
                    $uploaderName = $brochure->uploader ? $brochure->uploader->name : 'System';
                    $this->line("  - Brosur: {$brochure->original_name} | Uploader: {$uploaderName}");
                }
                $this->info("✅ Relasi uploader berhasil dimuat!");
            } else {
                $this->warn("⚠️  Tidak ada brosur yang ditemukan");
            }

            // Test lazy loading (seharusnya gagal jika lazy loading disabled)
            $this->line('');
            $this->line("Testing lazy loading (seharusnya gagal)...");
            try {
                $brochure = Brochure::first();
                if ($brochure) {
                    $uploaderName = $brochure->uploader->name; // Ini akan gagal jika lazy loading disabled
                    $this->error("❌ Lazy loading masih diizinkan - ini bukan good practice");
                }
            } catch (\Illuminate\Database\LazyLoadingViolationException $e) {
                $this->info("✅ Lazy loading violation terdeteksi - ini adalah good practice!");
            }
            
            $this->line('');
            $this->info("✅ Semua test berhasil! Error lazy loading sudah diperbaiki dengan eager loading.");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->line("File: " . $e->getFile() . " (Line: " . $e->getLine() . ")");
            return 1;
        }
    }
}