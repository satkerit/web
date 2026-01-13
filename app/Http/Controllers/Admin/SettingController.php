<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use AuthorizesAdminActions;

    public function maintenance()
    {
        $this->authorizeAny(['settings.maintenance']);

        $settings = SiteSetting::getSettings();
        $availablePages = SiteSetting::getAvailablePages();
        return view('admin.settings.maintenance', compact('settings', 'availablePages'));
    }

    public function updateMaintenance(Request $request)
    {
        $this->authorizeAny(['settings.maintenance']);

        $validated = $request->validate([
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string|max:1000',
            'maintenance_allowed_ips' => 'nullable|string',
            'maintenance_end_time' => 'nullable|date',
            'maintenance_pages' => 'nullable|array',
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');

        $settings = SiteSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            SiteSetting::create($validated);
        }

        SiteSetting::clearCache();

        return redirect()->route('admin.settings.maintenance')->with('success', 'Pengaturan maintenance berhasil diperbarui.');
    }
}
