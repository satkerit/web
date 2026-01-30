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
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.' . $extension;
        $fullPath = $storagePath . '/' . $filename;

        // For images that can be optimized
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            try {
                // Initialize ImageManager with GD driver
                $manager = new ImageManager(new Driver());

                // Read image
                $image = $manager->read($file);

                // Resize if needed (max 1920x1080)
                $maxWidth = 1920;
                $maxHeight = 1080;

                if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                    $image->scaleDown(width: $maxWidth, height: $maxHeight);
                }

                // Encode based on extension
                $encoded = match ($extension) {
                    'jpg', 'jpeg' => $image->toJpeg(quality: 85),
                    'png' => $image->toPng(),
                    'webp' => $image->toWebp(quality: 85),
                    default => $image->toJpeg(),
                };

                // Save to storage
                Storage::disk('public')->put($fullPath, (string) $encoded);
                return $fullPath;
            } catch (\Exception $e) {
                // Log error and fall back to normal upload
                Log::warning('Image optimization failed: ' . $e->getMessage());
            }
        }

        // Default: store without optimization
        return $file->store($storagePath, 'public');
    }

    /**
     * Handle logo uploads explicitly (skip optimization to preserve quality/transparency perfectly if needed)
     * Or just use the same method if confident.
     * For now, we'll keep the main logic in storeOptimizedImage and remove the old optimizeImage method.
     */
}
