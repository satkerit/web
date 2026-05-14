<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
        $deleteField = $fieldName . '_delete';
        $fromStorageField = $fieldName . '_from_storage';

        // Check if image should be deleted
        if ($request->input($deleteField) === '1') {
            // Delete old file if exists
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            return null;
        }

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
     * Store image with optimization using Intervention Image
     */
    protected function storeOptimizedImage(UploadedFile $file, string $storagePath): string
    {
        // Increase memory limit and execution time for image processing
        $originalMemoryLimit = ini_get('memory_limit');
        $originalTimeLimit = ini_get('max_execution_time');

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300); // 5 minutes

        try {
            $extension = strtolower($file->extension() ?: $file->getClientOriginalExtension());
            $filename = Str::uuid() . '.' . $extension;
            $fullPath = $storagePath . '/' . $filename;

            // Define allowed extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

            if (!in_array($extension, $allowedExtensions)) {
                throw new \Exception('Ekstensi file tidak diizinkan: ' . $extension);
            }

            // For images that can be optimized
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                try {
                    // Check file size before processing
                    $fileSize = $file->getSize();
                    if ($fileSize > 10 * 1024 * 1024) { // 10MB limit
                        throw new \Exception('File terlalu besar untuk diproses');
                    }

                    // Initialize ImageManager with GD driver
                    $manager = new ImageManager(new Driver());

                    // Read image with memory check
                    $image = $manager->read($file);

                    // Get original dimensions
                    $originalWidth = $image->width();
                    $originalHeight = $image->height();

                    // Skip processing if image is already small enough
                    $maxWidth = 1920;
                    $maxHeight = 1080;

                    if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight && $fileSize <= 1024 * 1024) {
                        // Image is already optimized, just store it
                        return $file->store($storagePath, 'public');
                    }

                    // Resize if needed (max 1920x1080)
                    if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                        $image->scaleDown(width: $maxWidth, height: $maxHeight);
                    }

                    // Encode based on extension with progressive optimization
                    $encoded = match ($extension) {
                        'jpg', 'jpeg' => $image->toJpeg(quality: 85, progressive: true),
                        'png' => $image->toPng(),
                        'webp' => $image->toWebp(quality: 85),
                        default => $image->toJpeg(quality: 85),
                    };

                    // Save to storage
                    Storage::disk('public')->put($fullPath, (string) $encoded);

                    // Log successful optimization
                    Log::info('Image optimized successfully', [
                        'original_size' => $fileSize,
                        'original_dimensions' => "{$originalWidth}x{$originalHeight}",
                        'final_path' => $fullPath
                    ]);

                    return $fullPath;
                } catch (\Exception $e) {
                    // Log error and fall back to normal upload
                    Log::warning('Image optimization failed: ' . $e->getMessage(), [
                        'file_size' => $file->getSize(),
                        'file_name' => $file->getClientOriginalName()
                    ]);

                    // Fall back to direct storage without optimization
                    return $file->store($storagePath, 'public');
                }
            }

            // Default: store without optimization
            return $file->store($storagePath, 'public');
        } finally {
            // Restore original limits
            ini_set('memory_limit', $originalMemoryLimit);
            ini_set('max_execution_time', $originalTimeLimit);
        }
    }

    /**
     * Handle logo uploads explicitly (skip optimization to preserve quality/transparency perfectly if needed)
     * Or just use the same method if confident.
     * For now, we'll keep the main logic in storeOptimizedImage and remove the old optimizeImage method.
     */
}
