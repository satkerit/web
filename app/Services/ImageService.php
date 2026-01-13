<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    protected $manager;
    protected $disk;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
        $this->disk = Storage::disk('public');
    }

    /**
     * Upload and resize hero slider image
     */
    public function uploadHeroSliderImage(UploadedFile $file, $folder = 'hero-slides')
    {
        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;

        // Create the image instance
        $image = $this->manager->read($file->getPathname());

        // Define responsive sizes for hero slider
        $sizes = [
            'original' => ['width' => 1920, 'height' => 1080, 'quality' => 90],
            'large' => ['width' => 1280, 'height' => 720, 'quality' => 85],
            'medium' => ['width' => 1024, 'height' => 576, 'quality' => 85],
            'small' => ['width' => 768, 'height' => 432, 'quality' => 80],
            'mobile' => ['width' => 480, 'height' => 270, 'quality' => 80],
        ];

        $generatedImages = [];

        foreach ($sizes as $sizeName => $config) {
            // Create resized image
            $resizedImage = clone $image;

            // Resize with aspect ratio maintained and crop to fit
            $resizedImage->cover($config['width'], $config['height']);

            // Generate filename for this size
            $sizeFilename = $sizeName === 'original'
                ? $filename
                : pathinfo($filename, PATHINFO_FILENAME) . '_' . $sizeName . '.' . pathinfo($filename, PATHINFO_EXTENSION);

            $sizePath = $folder . '/' . $sizeFilename;

            // Save the resized image
            $encodedImage = $resizedImage->toJpeg($config['quality']);
            $this->disk->put($sizePath, $encodedImage);

            $generatedImages[$sizeName] = $sizePath;
        }

        return [
            'original' => $generatedImages['original'],
            'sizes' => $generatedImages
        ];
    }

    /**
     * Delete hero slider images (all sizes)
     */
    public function deleteHeroSliderImage($imagePath)
    {
        if (!$imagePath) {
            return true;
        }

        // Delete original image
        if ($this->disk->exists($imagePath)) {
            $this->disk->delete($imagePath);
        }

        // Delete all size variants
        $pathInfo = pathinfo($imagePath);
        $baseFilename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $folder = $pathInfo['dirname'];

        $sizes = ['large', 'medium', 'small', 'mobile'];

        foreach ($sizes as $size) {
            $sizeFilename = $baseFilename . '_' . $size . '.' . $extension;
            $sizePath = $folder . '/' . $sizeFilename;

            if ($this->disk->exists($sizePath)) {
                $this->disk->delete($sizePath);
            }
        }

        return true;
    }

    /**
     * Get responsive image URLs
     */
    public function getResponsiveUrls($imagePath)
    {
        if (!$imagePath) {
            return [];
        }

        $pathInfo = pathinfo($imagePath);
        $baseFilename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $folder = $pathInfo['dirname'];

        $urls = [
            'original' => Storage::url($imagePath),
        ];

        $sizes = ['large', 'medium', 'small', 'mobile'];

        foreach ($sizes as $size) {
            $sizeFilename = $baseFilename . '_' . $size . '.' . $extension;
            $sizePath = $folder . '/' . $sizeFilename;

            if ($this->disk->exists($sizePath)) {
                $urls[$size] = Storage::url($sizePath);
            }
        }

        return $urls;
    }

    /**
     * Get srcset string for responsive images
     */
    public function getSrcset($imagePath)
    {
        $urls = $this->getResponsiveUrls($imagePath);

        if (empty($urls)) {
            return '';
        }

        $srcset = [];

        // Define width mappings
        $widthMappings = [
            'mobile' => '480w',
            'small' => '768w',
            'medium' => '1024w',
            'large' => '1280w',
            'original' => '1920w',
        ];

        foreach ($widthMappings as $size => $width) {
            if (isset($urls[$size])) {
                $srcset[] = $urls[$size] . ' ' . $width;
            }
        }

        return implode(', ', $srcset);
    }

    /**
     * Get the best image URL for a specific screen size
     */
    public function getImageForSize($imagePath, $screenSize = 'large')
    {
        $urls = $this->getResponsiveUrls($imagePath);

        // Return the requested size or fallback to original
        return $urls[$screenSize] ?? $urls['original'] ?? Storage::url($imagePath);
    }
}
