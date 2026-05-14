@extends('layouts.admin')

@section('title', 'Pengaturan Pengaduan Nasabah')

@section('content')
<x-admin.page-header title="Pengaturan Pengaduan Nasabah" subtitle="Konfigurasi sistem laporan pengaduan nasabah"/>

<div class="max-w-4xl">
    <form action="{{ route('admin.settings.complaint.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- Notifikasi Email --}}
            <x-admin.card title="Notifikasi Email">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email Penerima Notifikasi
                            </label>
                            <input type="email" name="admin_email"
                                   value="{{ old('admin_email', $settings->admin_email) }}"
                                   placeholder="admin@example.com"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('admin_email') border-red-300 @enderror">
                            @error('admin_email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Email yang menerima notifikasi pengaduan baru</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                CC Email
                            </label>
                            <input type="text" name="cc_emails"
                                   value="{{ old('cc_emails', $settings->cc_emails) }}"
                                   placeholder="cc1@example.com, cc2@example.com"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('cc_emails') border-red-300 @enderror">
                            @error('cc_emails')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Pisahkan beberapa email dengan koma</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Notifikasi Pengaduan Baru</p>
                                <p class="text-xs text-gray-500">Kirim email ke admin saat ada pengaduan baru masuk</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_on_new" value="1" class="sr-only peer"
                                       {{ old('notify_on_new', $settings->notify_on_new) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Notifikasi Perubahan Status</p>
                                <p class="text-xs text-gray-500">Kirim email ke admin saat status pengaduan berubah</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_on_status_change" value="1" class="sr-only peer"
                                       {{ old('notify_on_status_change', $settings->notify_on_status_change) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Konfirmasi ke Nasabah</p>
                                <p class="text-xs text-gray-500">Kirim email konfirmasi ke nasabah setelah pengaduan diterima</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="send_confirmation_to_customer" value="1" class="sr-only peer"
                                       {{ old('send_confirmation_to_customer', $settings->send_confirmation_to_customer) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </x-admin.card>

            {{-- SLA & Batas Waktu --}}
            <x-admin.card title="SLA & Batas Waktu Penanganan">
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Tentukan batas waktu penanganan (hari kerja) berdasarkan prioritas pengaduan.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Prioritas Rendah
                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">Rendah</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="sla_days_low" min="1" max="365"
                                       value="{{ old('sla_days_low', $settings->sla_days_low) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('sla_days_low') border-red-300 @enderror">
                                <span class="text-sm text-gray-500 whitespace-nowrap">hari</span>
                            </div>
                            @error('sla_days_low')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Prioritas Sedang
                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Sedang</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="sla_days_medium" min="1" max="365"
                                       value="{{ old('sla_days_medium', $settings->sla_days_medium) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('sla_days_medium') border-red-300 @enderror">
                                <span class="text-sm text-gray-500 whitespace-nowrap">hari</span>
                            </div>
                            @error('sla_days_medium')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Prioritas Tinggi
                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">Tinggi</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="sla_days_high" min="1" max="365"
                                       value="{{ old('sla_days_high', $settings->sla_days_high) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('sla_days_high') border-red-300 @enderror">
                                <span class="text-sm text-gray-500 whitespace-nowrap">hari</span>
                            </div>
                            @error('sla_days_high')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </x-admin.card>

            {{-- Pengaturan Form --}}
            <x-admin.card title="Pengaturan Form Pengaduan">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prefix Nomor Tiket</label>
                            <input type="text" name="ticket_prefix" maxlength="10"
                                   value="{{ old('ticket_prefix', $settings->ticket_prefix) }}"
                                   placeholder="ADU"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm uppercase @error('ticket_prefix') border-red-300 @enderror">
                            @error('ticket_prefix')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Contoh: ADU → ADU-20260418-XXXXXX</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-1">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Wajibkan Nomor Rekening</p>
                                <p class="text-xs text-gray-500">Nasabah harus mengisi nomor rekening saat mengadu</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="require_account_number" value="1" class="sr-only peer"
                                       {{ old('require_account_number', $settings->require_account_number) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Wajibkan Nomor Telepon</p>
                                <p class="text-xs text-gray-500">Nasabah harus mengisi nomor telepon</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="require_phone" value="1" class="sr-only peer"
                                       {{ old('require_phone', $settings->require_phone) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Izinkan Lampiran File</p>
                                <p class="text-xs text-gray-500">Nasabah dapat melampirkan dokumen pendukung</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="allow_attachments" value="1" class="sr-only peer"
                                       id="allow_attachments_toggle"
                                       {{ old('allow_attachments', $settings->allow_attachments) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Prioritas Otomatis</p>
                                <p class="text-xs text-gray-500">Sistem otomatis menentukan prioritas berdasarkan kategori</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="auto_assign_priority" value="1" class="sr-only peer"
                                       {{ old('auto_assign_priority', $settings->auto_assign_priority) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Pengaturan Lampiran --}}
                    <div id="attachment_settings" class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Maks. Jumlah Lampiran</label>
                            <input type="number" name="max_attachments" min="1" max="20"
                                   value="{{ old('max_attachments', $settings->max_attachments) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('max_attachments') border-red-300 @enderror">
                            @error('max_attachments')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Maks. per File (MB)</label>
                            <input type="number" name="max_file_size_mb" min="1" max="50"
                                   value="{{ old('max_file_size_mb', $settings->max_file_size_mb) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('max_file_size_mb') border-red-300 @enderror">
                            @error('max_file_size_mb')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe File Diizinkan</label>
                            <input type="text" name="allowed_file_types"
                                   value="{{ old('allowed_file_types', $settings->allowed_file_types) }}"
                                   placeholder="pdf,doc,docx,jpg,jpeg,png"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('allowed_file_types') border-red-300 @enderror">
                            @error('allowed_file_types')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Pisahkan dengan koma, tanpa titik</p>
                        </div>
                    </div>
                </div>
            </x-admin.card>

            {{-- Kategori Aktif --}}
            <x-admin.card title="Kategori Pengaduan Aktif">
                <div class="space-y-3">
                    <p class="text-sm text-gray-600">Pilih kategori yang tersedia untuk nasabah saat mengajukan pengaduan.</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($categories as $key => $label)
                            <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                <input type="checkbox" name="active_categories[]" value="{{ $key }}"
                                       {{ in_array($key, old('active_categories', $settings->active_categories ?? array_keys($categories))) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </x-admin.card>

            {{-- Teks & Konten --}}
            <x-admin.card title="Teks & Konten Form">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Pengantar Form</label>
                        <textarea name="form_intro_text" rows="3"
                                  placeholder="Tuliskan pengantar atau instruksi untuk nasabah sebelum mengisi form..."
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('form_intro_text') border-red-300 @enderror">{{ old('form_intro_text', $settings->form_intro_text) }}</textarea>
                        @error('form_intro_text')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Ditampilkan di bagian atas form pengaduan nasabah</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Sukses</label>
                        <textarea name="success_message" rows="3"
                                  placeholder="Pesan yang ditampilkan setelah nasabah berhasil mengirim pengaduan..."
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('success_message') border-red-300 @enderror">{{ old('success_message', $settings->success_message) }}</textarea>
                        @error('success_message')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Syarat & Ketentuan</label>
                        <textarea name="terms_text" rows="5"
                                  placeholder="Tuliskan syarat dan ketentuan pengajuan pengaduan..."
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('terms_text') border-red-300 @enderror">{{ old('terms_text', $settings->terms_text) }}</textarea>
                        @error('terms_text')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Ditampilkan sebagai checkbox persetujuan di form pengaduan</p>
                    </div>
                </div>
            </x-admin.card>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.customer-complaints.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                    Lihat Pengaduan
                </a>
                <x-admin.button type="submit">Simpan Pengaturan</x-admin.button>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script nonce="{{ $nonce }}">
    // Toggle attachment settings visibility
    const toggle = document.getElementById('allow_attachments_toggle');
    const attachmentSettings = document.getElementById('attachment_settings');

    function updateAttachmentVisibility() {
        attachmentSettings.style.opacity = toggle.checked ? '1' : '0.4';
        attachmentSettings.querySelectorAll('input').forEach(el => el.disabled = !toggle.checked);
    }

    toggle.addEventListener('change', updateAttachmentVisibility);
    updateAttachmentVisibility();

    // Auto uppercase ticket prefix
    document.querySelector('[name="ticket_prefix"]').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
</script>
@endpush
@endsection
