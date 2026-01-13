<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HeroImageService
{
    protected $imageManager;

    // Definisi ukuran responsif
    protected $responsiveSizes = [
        'desktop_large' => ['width' => 1920, 'height' => 800, 'quality' => 85],
        'desktop_medium' => ['width' => 1440, 'height' => 600, 'quality' => 85],
        'desktop_small' => ['width' => 1024, 'height' => 427, 'quality' => 80],
        'tablet' => ['width' => 768, 'height' => 480, 'quality' => 80],
        'mobile_large' => ['width' => 480, 'height' => 360, 'quality' => 75],
        'mobile_small' => ['width' => 320, 'height' => 240, 'quality' => 75],
    ];

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload dan generate semua ukuran responsif
     */
    public function uploadHeroImage(UploadedFile $file, string $filename = null): array
    {
        // Validasi gambar
        $this->validateImage($file);

        // Generate filename jika tidak disediakan
        if (!$filename) {
            $filename = time() . '_' . uniqid();
        }

        $uploadedImages = [];

        // Load gambar original
        $image = $this->imageManager->read($file->getPathname());

        // Generate setiap ukuran
        foreach ($this->responsiveSizes as $sizeName => $config) {
            // Resize dengan maintain aspect ratio
            $resizedImage = $image->clone()
                ->cover($config['width'], $config['height']);

            // Generate WebP
            $webpPath = "hero-images/{$filename}_{$sizeName}.webp";
            $webpEncoded = $resizedImage->toWebp($config['quality']);
            Storage::disk('public')->put($webpPath, $webpEncoded);

            // Generate JPG fallback
            $jpgPath = "hero-images/{$filename}_{$sizeName}.jpg";
            $jpgEncoded = $resizedImage->toJpeg($config['quality']);
            Storage::disk('public')->put($jpgPath, $jpgEncoded);

            $uploadedImages[$sizeName] = [
                'webp' => $webpPath,
                'jpg' => $jpgPath,
                'width' => $config['width'],
                'height' => $config['height']
            ];
        }

        return $uploadedImages;
    }

    /**
     * Validasi gambar sebelum upload
     */
    protected function validateImage(UploadedFile $file): void
    {
        // Cek ukuran minimum
        $imageSize = getimagesize($file->getPathname());
        if ($imageSize[0] < 320 || $imageSize[1] < 240) {
            throw new \InvalidArgumentException('Gambar terlalu kecil. Minimum 320x240px');
        }

        // Cek ukuran maksimum
        if ($imageSize[0] > 3840 || $imageSize[1] > 2160) {
            throw new \InvalidArgumentException('Gambar terlalu besar. Maksimum 3840x2160px');
        }

        // Cek ukuran file (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \InvalidArgumentException('File terlalu besar. Maksimum 5MB');
        }
    }

    /**
     * Generate HTML picture element
     */
    public function generatePictureElement(array $images, string $alt = 'Hero Image'): string
    {
        $html = '<picture class="hero-slider">';

        // Desktop Large
        if (isset($images['desktop_large'])) {
            $html .= '<source media="(min-width: 1920px)" srcset="' . Storage::url($images['desktop_large']['webp']) . '" type="image/webp">';
            $html .= '<source media="(min-width: 1920px)" srcset="' . Storage::url($images['desktop_large']['jpg']) . '">';
        }

        // Desktop Medium
        if (isset($images['desktop_medium'])) {
            $html .= '<source media="(min-width: 1440px)" srcset="' . Storage::url($images['desktop_medium']['webp']) . '" type="image/webp">';
            $html .= '<source media="(min-width: 1440px)" srcset="' . Storage::url($images['desktop_medium']['jpg']) . '">';
        }

        // Desktop Small
        if (isset($images['desktop_small'])) {
            $html .= '<source media="(min-width: 1024px)" srcset="' . Storage::url($images['desktop_small']['webp']) . '" type="image/webp">';
            $html .= '<source media="(min-width: 1024px)" srcset="' . Storage::url($images['desktop_small']['jpg']) . '">';
        }

        // Tablet
        if (isset($images['tablet'])) {
            $html .= '<source media="(min-width: 768px)" srcset="' . Storage::url($images['tablet']['webp']) . '" type="image/webp">';
            $html .= '<source media="(min-width: 768px)" srcset="' . Storage::url($images['tablet']['jpg']) . '">';
        }

        // Mobile Large
        if (isset($images['mobile_large'])) {
            $html .= '<source media="(min-width: 480px)" srcset="' . Storage::url($images['mobile_large']['webp']) . '" type="image/webp">';
            $html .= '<source media="(min-width: 480px)" srcset="' . Storage::url($images['mobile_large']['jpg']) . '">';
        }

        // Mobile Small (default)
        if (isset($images['mobile_small'])) {
            $html .= '<source srcset="' . Storage::url($images['mobile_small']['webp']) . '" type="image/webp">';
            $html .= '<img src="' . Storage::url($images['mobile_small']['jpg']) . '" alt="' . $alt . '" loading="lazy">';
        }

        $html .= '</picture>';

        return $html;
    }

    /**
     * Hapus semua ukuran gambar
     */
    public function deleteHeroImages(array $images): void
    {
        foreach ($images as $sizeImages) {
            if (isset($sizeImages['webp'])) {
                Storage::disk('public')->delete($sizeImages['webp']);
            }
            if (isset($sizeImages['jpg'])) {
                Storage::disk('public')->delete($sizeImages['jpg']);
            }
        }
    }
}
