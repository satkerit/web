<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyInfoController extends Controller
{
    use AuthorizesAdminActions, HandlesImageUpload;

    public function edit()
    {
        $this->authorizeAny(['settings.company']);

        $company = CompanyInfo::first() ?? new CompanyInfo();
        return view('admin.company-info.form', compact('company'));
    }

    public function update(Request $request)
    {
        $this->authorizeAny(['settings.company']);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'logo_footer' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'logo_footer_remove_bg' => 'nullable|boolean',
            'logo_footer_opacity' => 'nullable|integer|min:0|max:100',
            'favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg|max:512',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'email_contact' => 'nullable|email|max:255',
            'email_complaint' => 'nullable|email|max:255',
            'email_whistleblowing' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
            'organization_structure' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'stat_years_experience' => 'nullable|integer|min:0',
            'stat_branch_offices' => 'nullable|integer|min:0',
            'stat_total_assets' => 'nullable|string|max:100',
            'stat_cash_offices' => 'nullable|integer|min:0',
            'stat_mobile_cash_offices' => 'nullable|integer|min:0',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'ojk_license' => 'nullable|string|max:255',
            'ojk_tagline' => 'nullable|string|max:255',
            'lps_tagline' => 'nullable|string|max:255',
            'lps_guarantee_amount' => 'nullable|string|max:100',
            'footer_description' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:500',
            'operational_hours' => 'nullable|array',
        ]);

        $company = CompanyInfo::first();

        // Handle logo uploads WITHOUT optimization to preserve transparency
        $validated['logo'] = $this->handleLogoUpload($request, 'logo', 'company', $company?->logo);
        $validated['logo_footer'] = $this->handleLogoUpload($request, 'logo_footer', 'company', $company?->logo_footer);
        
        // Handle other image uploads with optimization
        $validated['favicon'] = $this->handleImageUpload($request, 'favicon', 'company', $company?->favicon);
        $validated['organization_structure'] = $this->handleImageUpload($request, 'organization_structure', 'company', $company?->organization_structure);

        if ($company) {
            $company->update($validated);
        } else {
            CompanyInfo::create($validated);
        }

        return redirect()->route('admin.company-info.edit')->with('success', 'Informasi perusahaan berhasil diperbarui.');
    }

    /**
     * Handle logo upload WITHOUT optimization to preserve PNG transparency
     */
    protected function handleLogoUpload(Request $request, string $fieldName, string $storagePath, ?string $oldPath = null): ?string
    {
        $fromStorageField = $fieldName . '_from_storage';

        // Check if image is selected from storage
        if ($request->filled($fromStorageField)) {
            $storageSrc = $request->input($fromStorageField);

            if (Storage::disk('public')->exists($storageSrc)) {
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

            // Store WITHOUT optimization to preserve transparency
            return $file->store($storagePath, 'public');
        }

        return $oldPath;
    }
}
