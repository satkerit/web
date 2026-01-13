@extends('layouts.admin')

@section('title', 'Pengaturan Maintenance')

@section('content')
<x-admin.page-header title="Pengaturan Maintenance" subtitle="Kelola mode maintenance website"/>

<div class="max-w-3xl">
    <form action="{{ route('admin.settings.maintenance.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <x-admin.card title="Mode Maintenance">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Aktifkan Mode Maintenance</p>
                            <p class="text-sm text-gray-500">Website akan menampilkan halaman maintenance untuk pengunjung</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer"
                                   {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Maintenance</label>
                        <textarea name="maintenance_message" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('maintenance_message', $settings->maintenance_message) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Pesan yang ditampilkan kepada pengunjung saat maintenance</p>
                    </div>

                    <x-admin.input type="datetime-local" name="maintenance_end_time" label="Waktu Selesai (Opsional)"
                                   :value="old('maintenance_end_time', $settings->maintenance_end_time?->format('Y-m-d\TH:i'))"
                                   hint="Maintenance akan otomatis nonaktif setelah waktu ini"/>
                </div>
            </x-admin.card>

            <x-admin.card title="IP yang Diizinkan">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Daftar IP</label>
                    <textarea name="maintenance_allowed_ips" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono" placeholder="192.168.1.1&#10;10.0.0.1">{{ old('maintenance_allowed_ips', $settings->maintenance_allowed_ips) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Satu IP per baris. IP ini tetap bisa mengakses website saat maintenance.</p>
                </div>
            </x-admin.card>

            <x-admin.card title="Maintenance Parsial">
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Pilih halaman yang ingin di-maintenance (kosongkan untuk maintenance seluruh website):</p>
                    
                    @php
                        $groupedPages = [
                            'Utama' => ['home'],
                            'Tentang Kami' => ['about', 'about_company', 'about_komisaris', 'about_direksi', 'about_dps', 'about_struktur', 'about_offices'],
                            'Produk & Layanan' => ['products', 'products_simpanan', 'products_pembiayaan', 'products_deposito', 'products_kas_keliling'],
                            'Informasi' => ['auctions', 'news', 'careers'],
                            'Laporan' => ['reports', 'reports_keuangan', 'reports_tata_kelola', 'reports_tahunan', 'reports_berkelanjutan'],
                            'Layanan Lainnya' => ['contact', 'whistleblowing', 'pengaduan_nasabah', 'download_logo', 'financing_simulation'],
                        ];
                    @endphp

                    @foreach($groupedPages as $groupName => $pageKeys)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                                <h4 class="font-medium text-gray-700 text-sm">{{ $groupName }}</h4>
                            </div>
                            <div class="p-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($pageKeys as $key)
                                    @if(isset($availablePages[$key]))
                                        <label class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors {{ str_starts_with($key, 'about_') || str_starts_with($key, 'products_') || str_starts_with($key, 'reports_') ? 'ml-4' : '' }}">
                                            <input type="checkbox" name="maintenance_pages[]" value="{{ $key }}"
                                                   {{ in_array($key, old('maintenance_pages', $settings->maintenance_pages ?? [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-sm text-gray-700">{{ $availablePages[$key]['name'] }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-xs text-amber-700">
                            <strong>Catatan:</strong> Memilih menu "(Semua)" akan menonaktifkan seluruh sub-menu di bawahnya.
                        </p>
                    </div>
                </div>
            </x-admin.card>

            <div class="flex justify-end">
                <x-admin.button type="submit">Simpan Pengaturan</x-admin.button>
            </div>
        </div>
    </form>
</div>
@endsection
