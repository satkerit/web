<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadTestBrochure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brochure:upload-test {product_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload brosur test ke produk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->argument('product_id');
        $product = Product::find($productId);

        if (!$product) {
            $this->error("Produk dengan ID {$productId} tidak ditemukan!");
            return 1;
        }

        // Cek apakah produk adalah pembiayaan syariah
        if ($product->type !== 'pembiayaan_syariah') {
            $this->error("Produk ini bukan produk pembiayaan syariah!");
            $this->line("Tipe produk: {$product->type}");
            return 1;
        }

        // Buat PDF test sederhana
        $pdfContent = $this->createTestPDF($product->name);
        $filename = 'brosur_' . str_replace(' ', '_', strtolower($product->name)) . '.pdf';
        $path = 'products/brochures/' . $filename;
        
        // Simpan file
        Storage::disk('public')->put($path, $pdfContent);

        // Update produk dengan path brosur
        $product->brochure = $path;
        $product->save();

        $this->info("✅ Brosur test berhasil diupload untuk produk: {$product->name}");
        $this->line("File: {$path}");
        $this->line("URL: " . Storage::disk('public')->url($path));
        
        return 0;
    }

    /**
     * Create a simple test PDF content
     */
    private function createTestPDF($productName)
    {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Brosur ' . $productName . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; color: #0f766e; margin-bottom: 30px; }
        .content { line-height: 1.6; }
        .footer { margin-top: 50px; text-align: center; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BPRS Bangka Belitung</h1>
        <h2>Brosur Produk Pembiayaan Syariah</h2>
    </div>
    
    <div class="content">
        <h3>' . $productName . '</h3>
        <p>
            Ini adalah brosur test untuk produk pembiayaan syariah ' . $productName . '.
            Produk ini merupakan bagian dari layanan pembiayaan syariah yang kami sediakan
            untuk membantu kebutuhan finansial Anda sesuai prinsip syariah.
        </p>
        
        <h4>Fitur Utama:</h4>
        <ul>
            <li>Sesuai prinsip syariah</li>
            <li>Proses cepat dan mudah</li>
            <li>Suku bunga kompetitif</li>
            <li>Pelayanan profesional</li>
        </ul>
        
        <h4>Hubungi Kami:</h4>
        <p>
            Untuk informasi lebih lanjut, silakan hubungi kami di:<br>
            📞 Telepon: (0717) 123-456<br>
            📧 Email: info@bprsyariah.com<br>
            🌐 Website: www.bprsyariah.com
        </p>
    </div>
    
    <div class="footer">
        <p>BPRS Bangka Belitung - Melayani dengan Iklas</p>
        <p>'. date('Y') .' - Hak Cipta Dilindungi</p>
    </div>
</body>
</html>';

        // Konversi HTML ke PDF (sederhana)
        // Note: Untuk produksi, gunakan library seperti DomPDF atau TCPDF
        return $html;
    }
}