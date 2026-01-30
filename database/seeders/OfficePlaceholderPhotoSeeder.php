<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Seeder untuk menambahkan foto placeholder ke kantor
 *
 * CATATAN: Ini adalah contoh seeder untuk testing/demonstrasi
 * Untuk production, upload foto asli melalui Admin Panel
 */
class OfficePlaceholderPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke foto placeholder
        $placeholderPhotos = [
            'pusat' => 'offices/placeholder-kantor-pusat.jpg',
            'cabang' => 'offices/placeholder-kantor-cabang.jpg',
            'kas' => 'offices/placeholder-kantor-kas.jpg',
            'kas_keliling' => 'offices/placeholder-kas-keliling.jpg',
        ];

        // Pastikan folder offices ada
        if (!Storage::disk('public')->exists('offices')) {
            Storage::disk('public')->makeDirectory('offices');
        }

        // Copy foto placeholder dari public/images ke storage (jika ada)
        // Atau bisa download dari URL, atau generate placeholder
        $this->createPlaceholderImages($placeholderPhotos);

        // Update kantor dengan foto placeholder
        foreach ($placeholderPhotos as $type => $photo) {
            Office::where('type', $type)
                ->whereNull('photo')
                ->update(['photo' => $photo]);
        }

        $this->command->info('✅ Foto placeholder berhasil ditambahkan ke kantor');
    }

    /**
     * Create placeholder images
     */
    private function createPlaceholderImages(array $placeholderPhotos): void
    {
        // Opsi 1: Copy dari folder public/images (jika ada)
        foreach ($placeholderPhotos as $type => $storagePath) {
            $publicPath = public_path("images/placeholders/office-{$type}.jpg");

            if (File::exists($publicPath)) {
                $content = File::get($publicPath);
                Storage::disk('public')->put($storagePath, $content);
                $this->command->info("✅ Copied placeholder for {$type}");
            }
        }

        // Opsi 2: Generate placeholder menggunakan Intervention Image
        // (Uncomment jika ingin generate otomatis)
        /*
        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());

        $colors = [
            'pusat' => '#f59e0b',      // Amber
            'cabang' => '#3b82f6',     // Blue
            'kas' => '#6b7280',        // Gray
            'kas_keliling' => '#14b8a6', // Teal
        ];

        foreach ($placeholderPhotos as $type => $storagePath) {
            if (!Storage::disk('public')->exists($storagePath)) {
                // Create 1200x800 placeholder
                $image = $manager->create(1200, 800);
                $image->fill($colors[$type]);

                // Add text
                $image->text(strtoupper($type), 600, 400, function ($font) {
                    $font->file(public_path('fonts/Roboto-Bold.ttf'));
                    $font->size(72);
                    $font->color('#ffffff');
                    $font->align('center');
                    $font->valign('middle');
                });

                // Save
                $encoded = $image->toJpeg(quality: 85);
                Storage::disk('public')->put($storagePath, (string) $encoded);
                $this->command->info("✅ Generated placeholder for {$type}");
            }
        }
        */

        // Opsi 3: Download dari placeholder service
        // (Uncomment jika ingin download dari service seperti placeholder.com)
        /*
        foreach ($placeholderPhotos as $type => $storagePath) {
            if (!Storage::disk('public')->exists($storagePath)) {
                $url = "https://via.placeholder.com/1200x800/3b82f6/ffffff?text=" . urlencode(strtoupper($type));
                $content = file_get_contents($url);

                if ($content) {
                    Storage::disk('public')->put($storagePath, $content);
                    $this->command->info("✅ Downloaded placeholder for {$type}");
                }
            }
        }
        */
    }
}
