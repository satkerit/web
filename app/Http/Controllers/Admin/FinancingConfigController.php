<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancingConfig;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FinancingConfigController extends Controller
{
    use AuthorizesAdminActions;

    /**
     * Display a listing of financing configs.
     */
    public function index()
    {
        $this->authorizeAny(['settings.financing']);

        $configs = FinancingConfig::orderBy('name')->get();

        return view('admin.financing-config.index', compact('configs'));
    }

    /**
     * Show the form for creating a new financing config.
     */
    public function create()
    {
        $this->authorizeAny(['settings.financing']);

        return view('admin.financing-config.form', [
            'config' => null,
        ]);
    }

    /**
     * Store a newly created financing config in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAny(['settings.financing']);

        $validated = $this->validateConfig($request);

        // Generate type from name
        $validated['type'] = Str::slug($validated['name'], '_');

        FinancingConfig::create($validated);

        return redirect()
            ->route('admin.financing-config.index')
            ->with('success', 'Konfigurasi pembiayaan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified financing config.
     */
    public function edit(FinancingConfig $financingConfig)
    {
        $this->authorizeAny(['settings.financing']);

        return view('admin.financing-config.form', [
            'config' => $financingConfig,
        ]);
    }

    /**
     * Update the specified financing config in storage.
     */
    public function update(Request $request, FinancingConfig $financingConfig)
    {
        $this->authorizeAny(['settings.financing']);

        $validated = $this->validateConfig($request);

        $financingConfig->update($validated);

        return redirect()
            ->route('admin.financing-config.index')
            ->with('success', 'Konfigurasi pembiayaan berhasil diperbarui.');
    }

    /**
     * Remove the specified financing config from storage.
     */
    public function destroy(FinancingConfig $financingConfig)
    {
        $this->authorizeAny(['settings.financing']);

        $financingConfig->delete();

        return redirect()
            ->route('admin.financing-config.index')
            ->with('success', 'Konfigurasi pembiayaan berhasil dihapus.');
    }

    /**
     * Validate financing config request
     */
    protected function validateConfig(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'margin_rate' => 'required|numeric|min:0.01|max:100',
            'min_principal' => 'required|integer|min:1',
            'max_principal' => 'required|integer|gt:min_principal',
            'available_tenors' => 'required|array|min:1',
            'available_tenors.*' => 'required|integer|min:1|max:360',
            'dp_enabled' => 'boolean',
            'dp_min_percentage' => 'nullable|numeric|min:0|max:100',
            'dp_max_percentage' => 'nullable|numeric|min:0|max:100|gte:dp_min_percentage',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama pembiayaan wajib diisi.',
            'margin_rate.required' => 'Margin rate wajib diisi.',
            'margin_rate.numeric' => 'Margin rate harus berupa angka.',
            'margin_rate.min' => 'Margin rate minimal 0.01%.',
            'margin_rate.max' => 'Margin rate maksimal 100%.',
            'min_principal.required' => 'Plafon minimal wajib diisi.',
            'min_principal.integer' => 'Plafon minimal harus berupa angka bulat.',
            'min_principal.min' => 'Plafon minimal harus lebih dari 0.',
            'max_principal.required' => 'Plafon maksimal wajib diisi.',
            'max_principal.integer' => 'Plafon maksimal harus berupa angka bulat.',
            'max_principal.gt' => 'Plafon maksimal harus lebih besar dari plafon minimal.',
            'available_tenors.required' => 'Tenor wajib diisi.',
            'available_tenors.array' => 'Tenor harus berupa array.',
            'available_tenors.min' => 'Minimal harus ada 1 tenor.',
            'available_tenors.*.integer' => 'Tenor harus berupa angka bulat.',
            'available_tenors.*.min' => 'Tenor minimal 1 bulan.',
            'available_tenors.*.max' => 'Tenor maksimal 360 bulan.',
            'dp_min_percentage.numeric' => 'DP minimal harus berupa angka.',
            'dp_min_percentage.min' => 'DP minimal tidak boleh kurang dari 0%.',
            'dp_min_percentage.max' => 'DP minimal tidak boleh lebih dari 100%.',
            'dp_max_percentage.numeric' => 'DP maksimal harus berupa angka.',
            'dp_max_percentage.min' => 'DP maksimal tidak boleh kurang dari 0%.',
            'dp_max_percentage.max' => 'DP maksimal tidak boleh lebih dari 100%.',
            'dp_max_percentage.gte' => 'DP maksimal harus lebih besar atau sama dengan DP minimal.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['dp_enabled'] = $request->boolean('dp_enabled');

        // Konversi margin rate dari persen ke desimal (16% -> 0.16)
        $validated['margin_rate'] = $validated['margin_rate'] / 100;

        // Set DP values to null if DP is disabled
        if (!$validated['dp_enabled']) {
            $validated['dp_min_percentage'] = null;
            $validated['dp_max_percentage'] = null;
        }

        // Sort tenors and remove duplicates
        $tenors = array_unique(array_map('intval', $validated['available_tenors']));
        sort($tenors);
        $validated['available_tenors'] = array_values($tenors);

        return $validated;
    }
}
