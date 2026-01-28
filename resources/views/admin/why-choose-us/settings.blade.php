@extends('layouts.admin')

@section('title', 'Pengaturan - Why Choose Us')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.why-choose-us.index') }}" class="p-2.5 bg-white rounded-xl text-slate-500 hover:text-slate-900 border border-slate-100 shadow-sm transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Section</h2>
            <p class="text-slate-500">Sesuaikan tampilan section "Why Choose Us" di halaman depan.</p>
        </div>
    </div>

    <form action="{{ route('admin.why-choose-us.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden">
             <!-- Decorative gradient -->
            <div class="absolute top-0 right-0 w-48 h-48 bg-teal-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>

            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </span>
                Konfigurasi Tampilan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Text Inputs -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Judul Section <span class="text-red-500">*</span></label>
                        <input type="text" name="section_title" value="{{ old('section_title', $settings->section_title) }}" required class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500/20 text-slate-900 border-2 font-bold transition-all shadow-sm">
                        <p class="text-xs text-slate-400">Judul besar yang muncul di bagian paling atas section.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Sub-Judul (Deskripsi Singkat) <span class="text-red-500">*</span></label>
                        <textarea name="section_subtitle" rows="4" required class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500/20 text-slate-900 transition-all resize-none shadow-sm">{{ old('section_subtitle', $settings->section_subtitle) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Teks Badge (Opsional)</label>
                        <input type="text" name="badge_text" value="{{ old('badge_text', $settings->badge_text) }}" class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500/20 text-slate-900 transition-all shadow-sm" placeholder="Contoh: 100% Syariah Compliant">
                        <p class="text-xs text-slate-400">Teks kecil yang muncul di atas judul atau pada kartu melayang.</p>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="space-y-6">
                    <div class="space-y-4">
                        <label class="text-sm font-semibold text-slate-700 block">Gambar Utama Section</label>
                        <div x-data="{ preview: '{{ $settings->section_image ? \App\Helpers\StorageHelper::url($settings->section_image) : null }}' }" class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-teal-400 hover:bg-teal-50/20 transition-all cursor-pointer relative group bg-slate-50/50 h-full min-h-[300px] flex flex-col justify-center items-center">
                            <input type="file" name="section_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/webp" @change="preview = URL.createObjectURL($event.target.files[0])">

                            <div x-show="!preview" class="space-y-3">
                                 <div class="w-16 h-16 bg-white border border-slate-100 shadow-sm text-slate-400 rounded-2xl flex items-center justify-center mx-auto group-hover:scale-110 group-hover:text-teal-500 transition-all duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                 </div>
                                 <div>
                                    <p class="text-sm font-semibold text-slate-600">Upload Gambar Section</p>
                                    <p class="text-xs text-slate-500 mt-1">Disarankan ukuran 600x700px atau portrait</p>
                                 </div>
                            </div>

                            <div x-show="preview" x-cloak class="relative w-full h-full">
                                <img :src="preview" class="w-full h-full object-contain rounded-lg max-h-[300px]">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                     <p class="text-white font-medium text-sm">Ganti Gambar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Toggle -->
             <div class="pt-6 mt-6 border-t border-slate-50">
                 <label class="flex items-center gap-4 cursor-pointer group p-3 rounded-xl border border-transparent hover:border-teal-100 hover:bg-teal-50/30 transition-all w-fit">
                    <div class="relative flex-shrink-0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $settings->is_active) ? 'checked' : '' }}>
                        <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500 after:shadow-sm"></div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-slate-800 block group-hover:text-teal-700 transition-colors">Tampilkan Section</span>
                        <span class="text-xs text-slate-500">Tampilkan atau sembunyikan seluruh section di halaman depan.</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4">
            <a href="{{ route('admin.why-choose-us.index') }}" class="px-6 py-3 bg-white text-slate-600 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all text-center shadow-sm">Kembali</a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-teal-500/30 hover:shadow-teal-500/40 hover:-translate-y-0.5 transition-all flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
