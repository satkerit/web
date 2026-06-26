@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<x-admin.page-header title="Pengaturan Website" subtitle="Kelola pengaturan umum website">
    <x-slot:actions>
        <x-admin.button type="submit" form="siteSettingsForm" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'>
            Simpan Perubahan
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card>
    <form id="siteSettingsForm" method="POST" action="{{ route('admin.site-settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Hero Slider Settings --}}
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Hero Slider</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hero_slider_delay" class="block text-sm font-medium text-gray-700 mb-2">
                        Delay Slider (milidetik)
                    </label>
                    <input type="number"
                           name="hero_slider_delay"
                           id="hero_slider_delay"
                           value="{{ old('hero_slider_delay', $settings->hero_slider_delay ?? 5000) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           min="1000"
                           max="20000"
                           step="500"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Durasi tampil setiap slide (1000-20000ms)</p>
                </div>

                <div>
                    <label for="hero_slide_limit" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Maksimal Slide
                    </label>
                    <input type="number"
                           name="hero_slide_limit"
                           id="hero_slide_limit"
                           value="{{ old('hero_slide_limit', $settings->hero_slide_limit ?? 5) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           min="1"
                           max="20"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Jumlah maksimal slide yang ditampilkan di halaman utama (1-20)</p>
                </div>
            </div>
        </div>

        {{-- Upload Settings --}}
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Upload File</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="upload_max_filesize" class="block text-sm font-medium text-gray-700 mb-2">
                        Ukuran Maksimal File Upload
                    </label>
                    <input type="text"
                           name="upload_max_filesize"
                           id="upload_max_filesize"
                           value="{{ old('upload_max_filesize', $settings->upload_max_filesize ?? '100M') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           placeholder="100M">
                    <p class="mt-1 text-sm text-gray-500">Ukuran maksimal file yang diupload (contoh: 100M, 2G)</p>
                </div>

                <div>
                    <label for="post_max_size" class="block text-sm font-medium text-gray-700 mb-2">
                        Ukuran Maksimal Post Data
                    </label>
                    <input type="text"
                           name="post_max_size"
                           id="post_max_size"
                           value="{{ old('post_max_size', $settings->post_max_size ?? '100M') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           placeholder="100M">
                    <p class="mt-1 text-sm text-gray-500">Ukuran maksimal data POST (contoh: 100M, 2G)</p>
                </div>

                <div>
                    <label for="max_execution_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Eksekusi Maksimal (detik)
                    </label>
                    <input type="number"
                           name="max_execution_time"
                           id="max_execution_time"
                           value="{{ old('max_execution_time', $settings->max_execution_time ?? 300) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           min="30"
                           max="3600">
                    <p class="mt-1 text-sm text-gray-500">Waktu maksimal eksekusi script (30-3600 detik)</p>
                </div>

                <div>
                    <label for="max_input_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Input Maksimal (detik)
                    </label>
                    <input type="number"
                           name="max_input_time"
                           id="max_input_time"
                           value="{{ old('max_input_time', $settings->max_input_time ?? 300) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           min="30"
                           max="3600">
                    <p class="mt-1 text-sm text-gray-500">Waktu maksimal menerima input (30-3600 detik)</p>
                </div>

                <div>
                    <label for="memory_limit" class="block text-sm font-medium text-gray-700 mb-2">
                        Batas Memori
                    </label>
                    <input type="text"
                           name="memory_limit"
                           id="memory_limit"
                           value="{{ old('memory_limit', $settings->memory_limit ?? '512M') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           placeholder="512M">
                    <p class="mt-1 text-sm text-gray-500">Batas memori script (contoh: 512M, 2G)</p>
                </div>

                <div>
                    <label for="max_file_uploads" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah File Upload Maksimal
                    </label>
                    <input type="number"
                           name="max_file_uploads"
                           id="max_file_uploads"
                           value="{{ old('max_file_uploads', $settings->max_file_uploads ?? 20) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           min="1"
                           max="100">
                    <p class="mt-1 text-sm text-gray-500">Jumlah file yang bisa diupload sekaligus (1-100)</p>
                </div>
            </div>
        </div>

        {{-- Report Page Settings --}}
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Halaman Laporan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="report_keuangan_publikasi_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Halaman Laporan Keuangan Publikasi
                    </label>
                    <input type="text"
                           name="report_keuangan_publikasi_title"
                           id="report_keuangan_publikasi_title"
                           value="{{ old('report_keuangan_publikasi_title', $settings->report_keuangan_publikasi_title ?? 'Laporan Keuangan Publikasi') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="report_keuangan_publikasi_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                        Subjudul Halaman Laporan Keuangan Publikasi
                    </label>
                    <input type="text"
                           name="report_keuangan_publikasi_subtitle"
                           id="report_keuangan_publikasi_subtitle"
                           value="{{ old('report_keuangan_publikasi_subtitle', $settings->report_keuangan_publikasi_subtitle ?? 'Laporan keuangan publikasi BPR Syariah') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="report_tata_kelola_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Halaman Laporan Tata Kelola
                    </label>
                    <input type="text"
                           name="report_tata_kelola_title"
                           id="report_tata_kelola_title"
                           value="{{ old('report_tata_kelola_title', $settings->report_tata_kelola_title ?? 'Laporan Tata Kelola') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="report_tata_kelola_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                        Subjudul Halaman Laporan Tata Kelola
                    </label>
                    <input type="text"
                           name="report_tata_kelola_subtitle"
                           id="report_tata_kelola_subtitle"
                           value="{{ old('report_tata_kelola_subtitle', $settings->report_tata_kelola_subtitle ?? 'Laporan tata kelola perusahaan') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="report_tahunan_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Halaman Laporan Tahunan
                    </label>
                    <input type="text"
                           name="report_tahunan_title"
                           id="report_tahunan_title"
                           value="{{ old('report_tahunan_title', $settings->report_tahunan_title ?? 'Laporan Tahunan') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="report_tahunan_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                        Subjudul Halaman Laporan Tahunan
                    </label>
                    <input type="text"
                           name="report_tahunan_subtitle"
                           id="report_tahunan_subtitle"
                           value="{{ old('report_tahunan_subtitle', $settings->report_tahunan_subtitle ?? 'Laporan tahunan BPR Syariah') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="report_tahunan_berkelanjutan_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Halaman Laporan Tahunan Berkelanjutan
                    </label>
                    <input type="text"
                           name="report_tahunan_berkelanjutan_title"
                           id="report_tahunan_berkelanjutan_title"
                           value="{{ old('report_tahunan_berkelanjutan_title', $settings->report_tahunan_berkelanjutan_title ?? 'Laporan Tahunan Berkelanjutan') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="report_tahunan_berkelanjutan_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                        Subjudul Halaman Laporan Tahunan Berkelanjutan
                    </label>
                    <input type="text"
                           name="report_tahunan_berkelanjutan_subtitle"
                           id="report_tahunan_berkelanjutan_subtitle"
                           value="{{ old('report_tahunan_berkelanjutan_subtitle', $settings->report_tahunan_berkelanjutan_subtitle ?? 'Laporan tahunan berkelanjutan BPR Syariah') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Maintenance Mode Settings --}}
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Maintenance Mode</h3>

            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox"
                           name="maintenance_mode"
                           id="maintenance_mode"
                           value="1"
                           {{ old('maintenance_mode', $settings->maintenance_mode ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="maintenance_mode" class="ml-2 text-sm font-medium text-gray-700">
                        Aktifkan Mode Pemeliharaan
                    </label>
                </div>

                <div id="maintenance_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ !old('maintenance_mode', $settings->maintenance_mode ?? false) ? 'hidden' : '' }}">
                    <div class="md:col-span-2">
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-2">
                            Pesan Maintenance
                        </label>
                        <textarea name="maintenance_message"
                                  id="maintenance_message"
                                  rows="3"
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('maintenance_message', $settings->maintenance_message ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="maintenance_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Selesai Maintenance
                        </label>
                        <input type="datetime-local"
                               name="maintenance_end_time"
                               id="maintenance_end_time"
                               value="{{ old('maintenance_end_time', $settings->maintenance_end_time ? $settings->maintenance_end_time->format('Y-m-d\\TH:i') : '') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="maintenance_allowed_ips" class="block text-sm font-medium text-gray-700 mb-2">
                            IP Address yang Diizinkan (satu per baris)
                        </label>
                        <textarea name="maintenance_allowed_ips"
                                  id="maintenance_allowed_ips"
                                  rows="3"
                                  placeholder="192.168.1.1&#10;10.0.0.1&#10;contoh.com"
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('maintenance_allowed_ips', $settings->maintenance_allowed_ips ?? '') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Kosongkan untuk memblokir semua IP</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Halaman yang Di-maintenance
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            @foreach(\App\Models\SiteSetting::getAvailablePages() as $key => $page)
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="maintenance_pages[]"
                                           value="{{ $key }}"
                                           {{ in_array($key, old('maintenance_pages', $settings->maintenance_pages ?? [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label class="ml-2 text-sm text-gray-700">{{ $page['name'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end pt-4">
            <x-admin.button type="submit" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'>
                Simpan Perubahan
            </x-admin.button>
        </div>
    </form>
</x-admin.card>

@push('scripts')
<script nonce="{{ $nonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const maintenanceModeCheckbox = document.getElementById('maintenance_mode');
    const maintenanceFields = document.getElementById('maintenance_fields');

    function toggleMaintenanceFields() {
        if (maintenanceModeCheckbox.checked) {
            maintenanceFields.classList.remove('hidden');
        } else {
            maintenanceFields.classList.add('hidden');
        }
    }

    maintenanceModeCheckbox.addEventListener('change', toggleMaintenanceFields);
    toggleMaintenanceFields(); // Initial state
});
</script>
@endpush
@endsection
