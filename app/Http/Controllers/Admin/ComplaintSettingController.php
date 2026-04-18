<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintSetting;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComplaintSettingController extends Controller
{
    use AuthorizesAdminActions;

    public function index()
    {
        $this->authorizeAny(['settings.complaints']);

        $settings   = ComplaintSetting::getSettings();
        $categories = ComplaintSetting::availableCategories();

        return view('admin.settings.complaint', compact('settings', 'categories'));
    }

    public function update(Request $request)
    {
        $this->authorizeAny(['settings.complaints']);

        $validated = $request->validate([
            'admin_email'                   => 'nullable|email|max:255',
            'cc_emails'                     => 'nullable|string|max:1000',
            'notify_on_new'                 => 'boolean',
            'notify_on_status_change'       => 'boolean',
            'send_confirmation_to_customer' => 'boolean',
            'sla_days_low'                  => 'required|integer|min:1|max:365',
            'sla_days_medium'               => 'required|integer|min:1|max:365',
            'sla_days_high'                 => 'required|integer|min:1|max:365',
            'require_account_number'        => 'boolean',
            'require_phone'                 => 'boolean',
            'allow_attachments'             => 'boolean',
            'max_attachments'               => 'required|integer|min:1|max:20',
            'max_file_size_mb'              => 'required|integer|min:1|max:50',
            'allowed_file_types'            => 'required|string|max:255',
            'ticket_prefix'                 => 'required|string|max:10|alpha',
            'auto_assign_priority'          => 'boolean',
            'form_intro_text'               => 'nullable|string|max:2000',
            'success_message'               => 'nullable|string|max:2000',
            'terms_text'                    => 'nullable|string|max:5000',
            'active_categories'             => 'nullable|array',
            'active_categories.*'           => 'in:service,product,transaction,facility,staff,other',
        ], [
            'ticket_prefix.alpha'           => 'Prefix tiket hanya boleh berisi huruf.',
            'sla_days_low.required'         => 'SLA prioritas rendah wajib diisi.',
            'sla_days_medium.required'      => 'SLA prioritas sedang wajib diisi.',
            'sla_days_high.required'        => 'SLA prioritas tinggi wajib diisi.',
            'max_attachments.required'      => 'Maksimal lampiran wajib diisi.',
            'max_file_size_mb.required'     => 'Ukuran maksimal file wajib diisi.',
            'allowed_file_types.required'   => 'Tipe file yang diizinkan wajib diisi.',
            'ticket_prefix.required'        => 'Prefix tiket wajib diisi.',
        ]);

        // Checkbox fields default false jika tidak dikirim
        $booleanFields = [
            'notify_on_new', 'notify_on_status_change', 'send_confirmation_to_customer',
            'require_account_number', 'require_phone', 'allow_attachments', 'auto_assign_priority',
        ];
        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field);
        }

        // Pastikan active_categories selalu array
        $validated['active_categories'] = $request->input('active_categories', []);

        // Bersihkan CC emails
        if (!empty($validated['cc_emails'])) {
            $ccList = array_filter(array_map('trim', explode(',', $validated['cc_emails'])));
            $validated['cc_emails'] = implode(', ', $ccList);
        }

        // Bersihkan allowed_file_types
        $fileTypes = array_filter(array_map('trim', explode(',', $validated['allowed_file_types'])));
        $validated['allowed_file_types'] = implode(',', $fileTypes);

        try {
            $settings = ComplaintSetting::first();
            if ($settings) {
                $settings->update($validated);
            } else {
                ComplaintSetting::create($validated);
            }

            ComplaintSetting::clearCache();

            return redirect()->route('admin.settings.complaint')
                ->with('success', 'Pengaturan pengaduan nasabah berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Error saving complaint settings: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }
}
