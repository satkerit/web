<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CompanyInfoController extends Controller
{
    use AuthorizesAdminActions;

    public function edit()
    {
        // Allow super admin and admin roles
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            $this->authorizeView('settings.company');
        }

        $company = CompanyInfo::first() ?? new CompanyInfo();

        return view('admin.company-info.form', compact('company'));
    }

    public function update(Request $request)
    {
        // Allow super admin and admin roles - maximum accessibility
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            try {
                $this->authorizeEdit('settings.company');
            } catch (\Exception $e) {
                Log::error('Company Info Update Authorization Failed', [
                    'user_id' => auth()->id(),
                    'user_email' => auth()->user()?->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Authorization failed: ' . $e->getMessage()
                    ], 403);
                }

                return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengedit data perusahaan.']);
            }
        }

        $company = CompanyInfo::first();

        try {
            $validated = $request->validate([
                // Basic Information
                'name' => 'required|string|max:255',
                'tagline' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),

                // Contact Information
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:50',
                'fax' => 'nullable|string|max:50',
                'whatsapp' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'email_contact' => 'nullable|email|max:255',
                'email_complaint' => 'nullable|email|max:255',
                'email_whistleblowing' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',

                // Visual Assets
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
                'logo_footer' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
                'logo_footer_remove_bg' => 'nullable|boolean',
                'logo_footer_opacity' => 'nullable|integer|min:0|max:100',
                'favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg|max:512',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'organization_structure' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',

                // Company Profile
                'vision' => 'nullable|string',
                'mission' => 'nullable|string',
                'history' => 'nullable|string',

                // Statistics
                'stat_years_experience' => 'nullable|integer|min:0',
                'stat_branch_offices' => 'nullable|integer|min:0',
                'stat_total_assets' => 'nullable|string|max:100',
                'stat_cash_offices' => 'nullable|integer|min:0',
                'stat_mobile_cash_offices' => 'nullable|integer|min:0',
                'legacy_visitor_count' => 'nullable|integer|min:0',

                // Social Media
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'tiktok' => 'nullable|url|max:255',

                // Regulatory Information
                'ojk_license' => 'nullable|string|max:255',
                'ojk_tagline' => 'nullable|string',
                'lps_tagline' => 'nullable|string',
                'lps_guarantee_amount' => 'nullable|string|max:100',

                // SEO & Footer
                'footer_description' => 'nullable|string|max:500',
                'meta_description' => 'nullable|string|max:255',
                'meta_keywords' => 'nullable|string|max:500',

                // Operational Hours
                'operational_hours' => 'nullable|array',
                'operational_hours.*.active' => 'nullable|boolean',
                'operational_hours.*.open' => 'nullable|string',
                'operational_hours.*.close' => 'nullable|string',
                'operational_hours.*.has_break' => 'nullable|boolean',
                'operational_hours.*.break_start' => 'nullable|string',
                'operational_hours.*.break_end' => 'nullable|string',
                'operational_hours.notes' => 'nullable|string',
            ]);

            // Handle file uploads using Laravel 12 approach
            $this->handleFileUploads($request, $validated, $company);

            // Update or create company info
            if ($company) {
                $company->update($validated);
            } else {
                CompanyInfo::create($validated);
            }

            Log::info('Company Info Updated Successfully', [
                'user_id' => auth()->id(),
                'company_id' => $company?->id
            ]);

            return redirect()->route('admin.company-info.edit')
                ->with('success', 'Informasi perusahaan berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Company Info Validation Failed', [
                'user_id' => auth()->id(),
                'errors' => $e->errors()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Company Info Update Failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Handle file uploads using Laravel 12 Storage approach
     */
    private function handleFileUploads(Request $request, array &$validated, ?CompanyInfo $company): void
    {
        $fileFields = [
            'logo' => 'company/logos',
            'logo_footer' => 'company/logos',
            'favicon' => 'company/icons',
            'profile_image' => 'company/profile',
            'organization_structure' => 'company/structure',
        ];

        foreach ($fileFields as $field => $path) {
            // Check if user wants to delete the file
            $deleteField = $field . '_delete';
            if ($request->input($deleteField) === '1' && $company && $company->$field) {
                Storage::disk('public')->delete($company->$field);
                $validated[$field] = null;
                continue;
            }

            // Handle new file upload
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($company && $company->$field) {
                    Storage::disk('public')->delete($company->$field);
                }

                // Store new file using Laravel 12 approach
                $file = $request->file($field);
                $filename = $this->generateUniqueFilename($file, $field);
                $validated[$field] = $file->storeAs($path, $filename, 'public');
            } elseif ($company) {
                // Keep existing file if no new upload and no deletion
                $validated[$field] = $company->$field;
            }
        }
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename($file, string $prefix): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);

        return "{$prefix}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Browse storage files (for file picker)
     */
    public function browseStorage(Request $request)
    {
        $this->authorizeView('settings.company');

        $path = $request->get('path', 'company');
        $type = $request->get('type', 'image');

        try {
            $files = Storage::disk('public')->files($path);
            $directories = Storage::disk('public')->directories($path);

            $items = [];

            // Add directories
            foreach ($directories as $dir) {
                $items[] = [
                    'name' => basename($dir),
                    'path' => $dir,
                    'type' => 'folder',
                    'url' => null,
                ];
            }

            // Add files based on type filter
            foreach ($files as $file) {
                if ($this->isValidFileType($file, $type)) {
                    $items[] = [
                        'name' => basename($file),
                        'path' => $file,
                        'type' => 'file',
                        'url' => Storage::url($file),
                        'size' => Storage::disk('public')->size($file),
                        'modified' => Storage::disk('public')->lastModified($file),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'path' => $path,
                'items' => $items,
            ]);
        } catch (\Exception $e) {
            Log::error('Storage browse error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error browsing storage',
                'items' => [],
            ], 500);
        }
    }

    /**
     * Check if file type is valid for the given filter
     */
    private function isValidFileType(string $filePath, string $type): bool
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($type) {
            'image' => in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']),
            'icon' => in_array($extension, ['ico', 'png', 'jpg', 'jpeg']),
            'document' => in_array($extension, ['pdf', 'doc', 'docx']),
            default => true,
        };
    }

    /**
     * Upload file via AJAX
     */
    public function uploadFile(Request $request)
    {
        $this->authorizeEdit('settings.company');

        $request->validate([
            'file' => 'required|file|max:5120',
            'path' => 'nullable|string',
            'type' => 'required|in:image,icon,document',
        ]);

        try {
            $file = $request->file('file');
            $path = $request->get('path', 'company');
            $type = $request->get('type', 'image');

            // Validate file type
            if (!$this->isValidFileType($file->getClientOriginalName(), $type)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type',
                ], 422);
            }

            $filename = $this->generateUniqueFilename($file, $type);
            $storedPath = $file->storeAs($path, $filename, 'public');

            return response()->json([
                'success' => true,
                'path' => $storedPath,
                'url' => Storage::url($storedPath),
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete file via AJAX
     */
    public function deleteFile(Request $request)
    {
        $this->authorizeDelete('settings.company');

        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->get('path');

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('File delete error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
