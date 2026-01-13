<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUpload
{
    /**
     * Handle image upload from file or storage selection
     *
     * @param Request $request
     * @param string $fieldName
     * @param string $storagePath
     * @param string|null $oldPath
     * @return string|null
     */
    protected function handleImageUpload(Request $request, string $fieldName, string $storagePath, ?string $oldPath = null): ?string
    {
        $fromStorageField = $fieldName . '_from_storage';

        // Check if image is selected from storage
        if ($request->filled($fromStorageField)) {
            $storageSrc = $request->input($fromStorageField);

            // Validate the storage path exists
            if (Storage::disk('public')->exists($storageSrc)) {
                // Delete old file if exists and different
                if ($oldPath && $oldPath !== $storageSrc) {
                    Storage::disk('public')->delete($oldPath);
                }
                return $storageSrc;
            }
        }

        // Check if new file is uploaded
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            // Delete old file if exists
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            // Optimize and store the image
            return $this->storeOptimizedImage($file, $storagePath);
        }

        // Return old path if no new image
        return $oldPath;
    }

    /**
     * Store image with optimization
     */
    protected function storeOptimizedImage(UploadedFile $file, string $storagePath): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        $fullPath = $storagePath . '/' . $filename;

        // For images that can be optimized
        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) {
            // Check if GD or Imagick is available
            if (extension_loaded('gd') || extension_loaded('imagick')) {
                try {
                    $optimizedContent = $this->optimizeImage($file);
                    if ($optimizedContent) {
                        Storage::disk('public')->put($fullPath, $optimizedContent);
                        return $fullPath;
                    }
                } catch (\Exception $e) {
                    // Fall back to normal upload if optimization fails
                    Log::warning('Image optimization failed: ' . $e->getMessage());
                }
            }
        }

        // Default: store without optimization
        return $file->store($storagePath, 'public');
    }

    /**
     * Optimize image using GD library
     */
    protected function optimizeImage(UploadedFile $file): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getPathname();

        // Create image resource based on type
        $image = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => @imagecreatefromwebp($path),
            default => null,
        };

        if (!$image) {
            return null;
        }

        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // Max dimensions (adjust as needed)
        $maxWidth = 1920;
        $maxHeight = 1080;

        // Calculate new dimensions if needed
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);

            // Create resized image
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG
            if ($extension === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefill($resized, 0, 0, $transparent);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Output to string
        ob_start();

        match ($extension) {
            'jpg', 'jpeg' => imagejpeg($image, null, 85), // 85% quality
            'png' => imagepng($image, null, 8), // Compression level 8
            'webp' => imagewebp($image, null, 85), // 85% quality
            default => null,
        };

        $content = ob_get_clean();
        imagedestroy($image);

        return $content ?: null;
    }
}
