<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class TestBrochureUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brochure:test-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test pembaharuan brosur dari produk pembiayaan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Testing Pembaharuan Brosur dari Produk Pembiayaan...");
            
            // Test produk pembiayaan dengan brosur
            $this->line("Mencari produk pembiayaan dengan brosur...");
            $pembiayaanProducts = Product::where('type', 'pembiayaan_syariah')
                ->where('is_active', true)
                ->whereNotNull('brochure')
                ->orderBy('order_position')
                ->orderBy('name')
                ->get();
            
            $this->info("✅ Ditemukan {$pembiayaanProducts->count()} produk pembiayaan dengan brosur");
            
            if ($pembiayaanProducts->count() > 0) {
                $this->line("Daftar produk:");
                foreach ($pembiayaanProducts as $product) {
                    $this->line("  - {$product->name} (brochure: " . ($product->brochure ? 'ada' : 'tidak ada') . ")");
                }
            } else {
                $this->warn("⚠️  Tidak ada produk pembiayaan dengan brosur");
            }

            // Test produk tanpa brosur
            $this->line('');
            $this->line("Mencari produk pembiayaan tanpa brosur...");
            $productsWithoutBrochure = Product::where('type', 'pembiayaan_syariah')
                ->where('is_active', true)
                ->whereNull('brochure')
                ->count();
            
            $this->info("✅ Ditemukan {$productsWithoutBrochure} produk pembiayaan tanpa brosur");

            // Test semua produk aktif
            $this->line('');
            $this->line("Mencari semua produk aktif...");
            $allActiveProducts = Product::where('is_active', true)->count();
            $this->info("✅ Total {$allActiveProducts} produk aktif");

            // Test semua jenis produk
            $this->line('');
            $this->line("Statistik produk berdasarkan tipe:");
            $productTypes = Product::select('type', \DB::raw('count(*) as total'))
                ->where('is_active', true)
                ->groupBy('type')
                ->get();
            
            foreach ($productTypes as $type) {
                $this->line("  - {$type->type}: {$type->total} produk");
            }
            
            $this->line('');
            $this->info("✅ Test pembaharuan brosur berhasil!");
            $this->line("Silakan cek halaman /brosur-pembiayaan-syariah untuk melihat hasilnya.");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->line("File: " . $e->getFile() . " (Line: " . $e->getLine() . ")");
            return 1;
        }
    }
}