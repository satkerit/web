<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageController extends Controller
{
    use AuthorizesAdminActions;

    protected $disk = 'public';
    protected $basePath = '';

    public function index(Request $request)
    {
        $this->authorizeView('storage.view');

        $path = $this->sanitizePath($request->get('path', ''));
        $items = $this->getDirectoryContents($path);
        $breadcrumbs = $this->getBreadcrumbs($path);
        $storageInfo = $this->getStorageInfo();

        return view('admin.storage.index', compact('items', 'path', 'breadcrumbs', 'storageInfo'));
    }

    public function upload(Request $request)
    {
        $this->authorizeEdit('storage.manage');

        $request->validate([
            'files.*' => 'required|file|max:51200|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,zip,rar', // Restrict file types
            'path' => 'nullable|string',
        ]);

        $path = $this->sanitizePath($request->get('path', ''));
        $uploaded = 0;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $this->generateUniqueFilename($path, $file->getClientOriginalName());
                $file->storeAs($path, $filename, $this->disk);
                $uploaded++;
            }
        }

        return redirect()->route('admin.storage.index', ['path' => $path])
            ->with('success', "{$uploaded} file berhasil diupload.");
    }

    public function createFolder(Request $request)
    {
        $this->authorizeEdit('storage.manage');

        $request->validate([
            'folder_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\-_]+$/',
            'path' => 'nullable|string',
        ]);

        $path = $this->sanitizePath($request->get('path', ''));
        $folderName = $request->folder_name;
        $fullPath = $path ? "{$path}/{$folderName}" : $folderName;

        if (Storage::disk($this->disk)->exists($fullPath)) {
            return back()->with('error', 'Folder sudah ada.');
        }

        Storage::disk($this->disk)->makeDirectory($fullPath);

        return redirect()->route('admin.storage.index', ['path' => $path])
            ->with('success', 'Folder berhasil dibuat.');
    }

    public function delete(Request $request)
    {
        $this->authorizeDelete('storage.manage');

        $request->validate([
            'item' => 'required|string',
            'type' => 'required|in:file,folder',
        ]);

        $item = $this->sanitizePath($request->item);

        if (!Storage::disk($this->disk)->exists($item)) {
            return back()->with('error', 'Item tidak ditemukan.');
        }

        if ($request->type === 'folder') {
            Storage::disk($this->disk)->deleteDirectory($item);
            $message = 'Folder berhasil dihapus.';
        } else {
            Storage::disk($this->disk)->delete($item);
            $message = 'File berhasil dihapus.';
        }

        $parentPath = dirname($item);
        $parentPath = $parentPath === '.' ? '' : $parentPath;

        return redirect()->route('admin.storage.index', ['path' => $parentPath])
            ->with('success', $message);
    }


    public function download(Request $request)
    {
        $this->authorizeView('storage.view');

        $file = $this->sanitizePath($request->get('file', ''));

        if (!Storage::disk($this->disk)->exists($file)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Check if it's a directory by trying to get files from it
        $fullPath = Storage::disk($this->disk)->path($file);
        if (is_dir($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($fullPath, basename($file));
    }

    public function rename(Request $request)
    {
        $this->authorizeEdit('storage.manage');

        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:255',
            'type' => 'required|in:file,folder',
        ]);

        $oldPath = $this->sanitizePath($request->old_name);
        $parentPath = dirname($oldPath);
        $parentPath = $parentPath === '.' ? '' : $parentPath;

        $newName = $request->type === 'file'
            ? $this->sanitizeFilename($request->new_name)
            : preg_replace('/[^a-zA-Z0-9\-_]/', '', $request->new_name);

        $newPath = $parentPath ? "{$parentPath}/{$newName}" : $newName;

        if (!Storage::disk($this->disk)->exists($oldPath)) {
            return back()->with('error', 'Item tidak ditemukan.');
        }

        if (Storage::disk($this->disk)->exists($newPath)) {
            return back()->with('error', 'Nama sudah digunakan.');
        }

        Storage::disk($this->disk)->move($oldPath, $newPath);

        return redirect()->route('admin.storage.index', ['path' => $parentPath])
            ->with('success', 'Berhasil diubah namanya.');
    }

    protected function getDirectoryContents(string $path): array
    {
        $directories = Storage::disk($this->disk)->directories($path);
        $files = Storage::disk($this->disk)->files($path);

        $items = [];

        foreach ($directories as $dir) {
            $items[] = [
                'name' => basename($dir),
                'path' => $dir,
                'type' => 'folder',
                'size' => null,
                'modified' => Storage::disk($this->disk)->lastModified($dir),
            ];
        }

        foreach ($files as $file) {
            if (basename($file) === '.gitignore') continue;

            $items[] = [
                'name' => basename($file),
                'path' => $file,
                'type' => 'file',
                'size' => Storage::disk($this->disk)->size($file),
                'modified' => Storage::disk($this->disk)->lastModified($file),
                'extension' => pathinfo($file, PATHINFO_EXTENSION),
                'url' => asset('storage/' . $file),
            ];
        }

        return $items;
    }

    protected function getBreadcrumbs(string $path): array
    {
        if (empty($path)) return [];

        $parts = explode('/', $path);
        $breadcrumbs = [];
        $currentPath = '';

        foreach ($parts as $part) {
            $currentPath = $currentPath ? "{$currentPath}/{$part}" : $part;
            $breadcrumbs[] = [
                'name' => $part,
                'path' => $currentPath,
            ];
        }

        return $breadcrumbs;
    }

    protected function getStorageInfo(): array
    {
        $storagePath = Storage::disk($this->disk)->path('');

        return [
            'total' => disk_total_space($storagePath),
            'free' => disk_free_space($storagePath),
            'used' => disk_total_space($storagePath) - disk_free_space($storagePath),
        ];
    }

    protected function sanitizePath(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // Normalize slashes
        $path = str_replace('\\', '/', $path);

        // Split path into segments
        $segments = explode('/', $path);

        // Filter out '..' and '.' to prevent directory traversal
        $safeSegments = array_filter($segments, function ($segment) {
            return $segment !== '..' && $segment !== '.' && $segment !== '';
        });

        // Rebuild path
        return implode('/', $safeSegments);
    }

    protected function sanitizeFilename(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = Str::slug($name);
        return $ext ? "{$name}.{$ext}" : $name;
    }

    protected function generateUniqueFilename(string $path, string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $safeName = Str::slug($name);
        $finalName = $ext ? "{$safeName}.{$ext}" : $safeName;

        $fullPath = $path ? "{$path}/{$finalName}" : $finalName;
        $counter = 1;

        while (Storage::disk($this->disk)->exists($fullPath)) {
            $finalName = $ext ? "{$safeName}-{$counter}.{$ext}" : "{$safeName}-{$counter}";
            $fullPath = $path ? "{$path}/{$finalName}" : $finalName;
            $counter++;
        }

        return $finalName;
    }

    /**
     * API endpoint for browsing storage (used by image picker component)
     * This is more permissive than other storage actions because it's used
     * by the image picker component across various forms (news, products, company info, etc.)
     */
    public function apiBrowse(Request $request)
    {
        // Any authenticated admin can browse storage for image selection
        // This is a read-only operation used by image picker components
        $this->authorizeAdmin();

        try {
            $path = $this->sanitizePath($request->get('path', ''));

            // Check if path exists (empty path is root, always valid)
            if ($path !== '' && !Storage::disk($this->disk)->exists($path)) {
                return response()->json([
                    'error' => 'Path tidak ditemukan.',
                    'path' => $path,
                    'items' => [],
                ], 404);
            }

            $items = $this->getDirectoryContentsForApi($path);

            return response()->json([
                'path' => $path,
                'items' => $items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memuat direktori: ' . $e->getMessage(),
                'path' => $request->get('path', ''),
                'items' => [],
            ], 500);
        }
    }

    protected function getDirectoryContentsForApi(string $path): array
    {
        $directories = Storage::disk($this->disk)->directories($path);
        $files = Storage::disk($this->disk)->files($path);

        $items = [];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        foreach ($directories as $dir) {
            $items[] = [
                'name' => basename($dir),
                'path' => $dir,
                'type' => 'folder',
            ];
        }

        foreach ($files as $file) {
            if (basename($file) === '.gitignore') continue;

            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $isImage = in_array($extension, $imageExtensions);

            $items[] = [
                'name' => basename($file),
                'path' => $file,
                'type' => 'file',
                'extension' => $extension,
                'url' => asset('storage/' . $file),
                'isImage' => $isImage,
            ];
        }

        return $items;
    }

    /**
     * Upload image from WYSIWYG editor (Summernote)
     * Returns JSON response with image URL
     */
    public function uploadEditorImage(Request $request)
    {
        try {
            // Validate request
            $validator = \Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120', // Max 5MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file provided'
                ], 400);
            }

            $file = $request->file('image');

            // Check if file is valid
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload'
                ], 400);
            }

            $path = 'news/editor-images';

            // Create directory if not exists
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            // Generate unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Store file
            $storedPath = $file->storeAs($path, $filename, 'public');

            if (!$storedPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store file'
                ], 500);
            }

            // Get full URL
            $url = asset('storage/' . $storedPath);

            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $storedPath,
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            \Log::error('Editor image upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }
}

