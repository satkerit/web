@extends('layouts.admin')

@section('title', $config ? 'Edit Konfigurasi Pembiayaan' : 'Tambah Konfigurasi Pembiayaan')

@section('content')
<x-admin.page-header
    :title="$config ? 'Edit Konfigurasi Pembiayaan' : 'Tambah Konfigurasi Pembiayaan'"
    :subtitle="$config ? 'Ubah parameter perhitungan ' . $config->name : 'Tambah jenis pembiayaan baru'"
>
    <x-slot:actions>
        <a href="{{ route('admin.financing-config.index') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 bg-white rounded-xl ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </x-slot:actions>
</x-admin.page-header>

@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
    <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-red-800 mb-2">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Form --}}
    <div class="lg:col-span-2">
        <form action="{{ $config ? route('admin.financing-config.update', $config) : route('admin.financing-config.store') }}" method="POST">
            @csrf
            @if($config)
                @method('PUT')
            @endif

            <div class="space-y-6">
                {{-- Informasi Dasar --}}
                <x-admin.card title="Informasi Dasar">
                    <div class="space-y-4">
                        @if($config)
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Pembiayaan</label>
                            <input type="text" value="{{ $config->type }}" disabled class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-500 bg-slate-100 shadow-sm ring-1 ring-inset ring-slate-200 sm:text-sm cursor-not-allowed">
                            <p class="mt-1.5 text-xs text-slate-500">Tipe pembiayaan tidak dapat diubah</p>
                        </div>
                        @endif

                        <x-admin.input
                            name="name"
                            label="Nama Pembiayaan"
                            :value="old('name', $config?->name)"
                            required
                            placeholder="Contoh: Pembiayaan Murabahah"
                            :error="$errors->first('name')"
                        />
                    </div>
                </x-admin.card>

                {{-- Parameter Perhitungan --}}
                <x-admin.card title="Parameter Perhitungan">
                    <div class="space-y-4">
                        <div>
                            <label for="calculation_type" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Tipe Perhitungan <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="calculation_type"
                                id="calculation_type"
                                required
                                class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm {{ $errors->has('calculation_type') ? 'ring-red-300 focus:ring-red-500 bg-red-50/50' : 'hover:ring-slate-300' }}"
                            >
                                <option value="">-- Pilih Tipe Perhitungan --</option>
                                <option value="margin" {{ old('calculation_type', $config?->calculation_type) === 'margin' ? 'selected' : '' }}>
                                    Margin (Flat Rate)
                                </option>
                                <option value="profit_sharing" {{ old('calculation_type', $config?->calculation_type) === 'profit_sharing' ? 'selected' : '' }}>
                                    Bagi Hasil (Profit Sharing)
                                </option>
                            </select>
                            <p class="mt-1.5 text-xs text-slate-500">
                                <strong>Margin:</strong> Perhitungan berdasarkan plafond pembiayaan<br>
                                <strong>Bagi Hasil:</strong> Perhitungan berdasarkan proyeksi keuntungan proyek
                            </p>
                            @error('calculation_type')
                                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="margin_rate" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Margin Rate (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="margin_rate"
                                    id="margin_rate"
                                    value="{{ old('margin_rate', $config ? $config->margin_rate * 100 : '') }}"
                                    step="0.01"
                                    min="0.01"
                                    max="100"
                                    required
                                    class="block w-full rounded-xl border-0 py-2.5 px-4 pr-12 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm {{ $errors->has('margin_rate') ? 'ring-red-300 focus:ring-red-500 bg-red-50/50' : 'hover:ring-slate-300' }}"
                                    placeholder="12.00"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span class="text-slate-500 text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500">Margin rate per tahun (contoh: 12 untuk 12%)</p>
                            @error('margin_rate')
                                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="min_principal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Plafon Minimal (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    name="min_principal"
                                    id="min_principal"
                                    value="{{ old('min_principal', $config?->min_principal) }}"
                                    min="1"
                                    required
                                    class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm {{ $errors->has('min_principal') ? 'ring-red-300 focus:ring-red-500 bg-red-50/50' : 'hover:ring-slate-300' }}"
                                    placeholder="5000000"
                                >
                                @error('min_principal')
                                    <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_principal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Plafon Maksimal (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    name="max_principal"
                                    id="max_principal"
                                    value="{{ old('max_principal', $config?->max_principal) }}"
                                    min="1"
                                    required
                                    class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm {{ $errors->has('max_principal') ? 'ring-red-300 focus:ring-red-500 bg-red-50/50' : 'hover:ring-slate-300' }}"
                                    placeholder="500000000"
                                >
                                @error('max_principal')
                                    <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </x-admin.card>

                {{-- Tenor --}}
                <x-admin.card title="Tenor Tersedia">
                    <div class="space-y-4">
                        <p class="text-sm text-slate-600">Pilih tenor (jangka waktu) yang tersedia untuk jenis pembiayaan ini:</p>

                        @php
                            $tenorOptions = [6, 12, 18, 24, 30, 36, 48, 60, 72, 84, 96, 120];
                            $selectedTenors = old('available_tenors', $config?->available_tenors ?? []);
                        @endphp

                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                            @foreach($tenorOptions as $tenor)
                                <label class="relative flex items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all {{ in_array($tenor, $selectedTenors) ? 'border-blue-500 bg-green-50' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                    <input
                                        type="checkbox"
                                        name="available_tenors[]"
                                        value="{{ $tenor }}"
                                        {{ in_array($tenor, $selectedTenors) ? 'checked' : '' }}
                                        class="sr-only peer"
                                        onchange="this.closest('label').classList.toggle('border-blue-500', this.checked); this.closest('label').classList.toggle('bg-green-50', this.checked); this.closest('label').classList.toggle('border-slate-200', !this.checked);"
                                    >
                                    <span class="text-sm font-medium {{ in_array($tenor, $selectedTenors) ? 'text-green-700' : 'text-slate-700' }}">{{ $tenor }} bln</span>
                                </label>
                            @endforeach
                        </div>

                        @error('available_tenors')
                            <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                        @error('available_tenors.*')
                            <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror

                        <p class="text-xs text-slate-500">Minimal pilih 1 tenor. Tenor akan ditampilkan pada halaman simulasi pembiayaan.</p>
                    </div>
                </x-admin.card>

                {{-- Down Payment --}}
                <x-admin.card title="Down Payment (DP)">
                    <div class="space-y-4" x-data="{ dpEnabled: @js(old('dp_enabled', $config?->dp_enabled ?? false)) }">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-900">Aktifkan Input DP</p>
                                <p class="text-sm text-slate-500">Jika diaktifkan, nasabah dapat memasukkan DP pada simulasi</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="dp_enabled" value="0">
                                <input
                                    type="checkbox"
                                    name="dp_enabled"
                                    value="1"
                                    x-model="dpEnabled"
                                    {{ old('dp_enabled', $config?->dp_enabled ?? false) ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div x-show="dpEnabled" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                            <div>
                                <label for="dp_min_percentage" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    DP Minimal (%)
                                </label>
                                <div class="relative">
                                    <input
                                        type="number"
                                        name="dp_min_percentage"
                                        id="dp_min_percentage"
                                        value="{{ old('dp_min_percentage', $config?->dp_min_percentage) }}"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        class="block w-full rounded-xl border-0 py-2.5 px-4 pr-12 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm {{ $errors->has('dp_min_percentage') ? 'ring-red-300 focus:ring-red-500 bg-red-50/50' : 'hover:ring-slate-300' }}"
                                        placeholder="10"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-slate-500 text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1.5 text-xs text-slate-500">Kosongkan jika tidak ada batas minimal</p>
                                @error('dp_min_percentage')
                                    <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="dp_max_percentage" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    DP Maksimal (%)
                                </label>
                                <div class="relative">
                                    <input
                                        type="number"
                                        name="dp_max_percentage"
                                        id="dp_max_percentage"
                                        value="{{ old('dp_max_percentage', $config?->dp_max_percentage) }}"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        class="block w-full rounded-xl border-0 py-2.5 px-4 pr-12 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm {{ $errors->has('dp_max_percentage') ? 'ring-red-300 focus:ring-red-500 bg-red-50/50' : 'hover:ring-slate-300' }}"
                                        placeholder="50"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-slate-500 text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1.5 text-xs text-slate-500">Kosongkan jika tidak ada batas maksimal</p>
                                @error('dp_max_percentage')
                                    <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </x-admin.card>

                {{-- Status --}}
                <x-admin.card title="Status">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-900">Aktifkan Konfigurasi</p>
                            <p class="text-sm text-slate-500">Jika dinonaktifkan, jenis pembiayaan ini tidak akan muncul di halaman simulasi</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $config?->is_active ?? true) ? 'checked' : '' }}
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </x-admin.card>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.financing-config.index') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 bg-white rounded-xl ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <x-admin.button type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $config ? 'Simpan Perubahan' : 'Tambah Pembiayaan' }}
                    </x-admin.button>
                </div>
            </div>
        </form>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        @if($config)
        {{-- Preview Calculation --}}
        <x-admin.card title="Preview Perhitungan">
            <div class="space-y-4">
                <p class="text-sm text-slate-600">Contoh perhitungan dengan konfigurasi saat ini:</p>

                <div class="p-4 bg-slate-50 rounded-xl space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Pokok Pembiayaan:</span>
                        <span class="font-medium text-slate-900">Rp 100.000.000</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Margin Rate:</span>
                        <span class="font-medium text-slate-900">{{ number_format($config->margin_rate * 100, 2) }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tenor:</span>
                        <span class="font-medium text-slate-900">12 bulan</span>
                    </div>
                    <hr class="border-slate-200">
                    @php
                        $examplePrincipal = 100000000;
                        $exampleTenor = 12;
                        $totalMargin = $examplePrincipal * $config->margin_rate * ($exampleTenor / 12);
                        $totalPayment = $examplePrincipal + $totalMargin;
                        $monthlyInstallment = $totalPayment / $exampleTenor;
                    @endphp
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Total Margin:</span>
                        <span class="font-medium text-blue-600">Rp {{ number_format($totalMargin, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Total Pembayaran:</span>
                        <span class="font-medium text-slate-900">Rp {{ number_format($totalPayment, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-slate-700">Angsuran/Bulan:</span>
                        <span class="text-blue-600">Rp {{ number_format($monthlyInstallment, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </x-admin.card>
        @endif

        {{-- Formula Info --}}
        <x-admin.card title="Formula Perhitungan">
            <div class="space-y-3 text-sm">
                <div class="p-3 bg-green-50 rounded-lg">
                    <p class="font-medium text-blue-800 mb-2">Flat Rate Formula:</p>
                    <code class="text-xs text-green-700 block">
                        Angsuran = (P + (P × M × T/12)) / T
                    </code>
                </div>
                <div class="text-slate-600 space-y-1">
                    <p><strong>P</strong> = Pokok Pembiayaan</p>
                    <p><strong>M</strong> = Margin Rate (desimal)</p>
                    <p><strong>T</strong> = Tenor (bulan)</p>
                </div>
            </div>
        </x-admin.card>

        {{-- Last Updated --}}
        @if($config)
        <x-admin.card title="Informasi">
            <div class="space-y-2 text-sm text-slate-600">
                <div class="flex justify-between">
                    <span>Dibuat:</span>
                    <span class="text-slate-900">{{ $config->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Terakhir diubah:</span>
                    <span class="text-slate-900">{{ $config->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </x-admin.card>
        @endif
    </div>
</div>

@push('scripts')
<script nonce="{{ $nonce }}">
    // No conversion needed - controller handles percentage to decimal conversion
</script>
@endpush
@endsection
