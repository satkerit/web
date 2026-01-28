@extends('layouts.admin')

@section('title', 'Edit Item - Why Choose Us')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.why-choose-us.index') }}" class="p-2.5 bg-white rounded-xl text-slate-500 hover:text-slate-900 border border-slate-100 shadow-sm transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Item</h2>
            <p class="text-slate-500">Perbarui informasi poin keunggulan.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.why-choose-us.update', $whyChooseUs->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden">
             <!-- Decorative gradient -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>

            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </span>
                Informasi Utama
            </h3>

            <!-- Title & Order -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Judul Item <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $whyChooseUs->title) }}" required class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/20 text-slate-900 placeholder:text-slate-400 font-medium transition-all shadow-sm">
                    @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Urutan <span class="text-red-500">*</span></label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $whyChooseUs->sort_order) }}" min="0" required class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/20 text-slate-900 font-medium transition-all shadow-sm">
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2 mb-6">
                <label class="text-sm font-semibold text-slate-700">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="3" required class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/20 text-slate-900 placeholder:text-slate-400 font-medium transition-all resize-none shadow-sm">{{ old('description', $whyChooseUs->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Visualization -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-50 mt-4">
                 <!-- Icon Upload -->
                <div class="space-y-4">
                     <label class="text-sm font-semibold text-slate-700 flex justify-between items-center">
                        <span>Icon Image (Opsional)</span>
                        <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">Max: 2MB</span>
                     </label>
                     <div x-data="{ preview: '{{ $whyChooseUs->icon ? \App\Helpers\StorageHelper::url($whyChooseUs->icon) : null }}' }" class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-indigo-400 hover:bg-indigo-50/20 transition-all cursor-pointer relative group bg-slate-50/50">
                        <input type="file" name="icon" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/svg+xml, image/webp" @change="preview = URL.createObjectURL($event.target.files[0])">

                        <div x-show="!preview" class="space-y-3">
                             <div class="w-14 h-14 bg-white border border-slate-100 shadow-sm text-slate-400 rounded-2xl flex items-center justify-center mx-auto group-hover:scale-110 group-hover:text-indigo-500 transition-all duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                             </div>
                             <div>
                                <p class="text-sm font-semibold text-slate-600">Upload Icon Baru</p>
                                <p class="text-xs text-slate-500 mt-1">SVG, PNG, JPG, WebP</p>
                             </div>
                        </div>

                        <div x-show="preview" x-cloak class="relative">
                            <img :src="preview" class="h-32 mx-auto object-contain rounded-lg shadow-sm bg-white p-2">
                             <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                 <p class="text-white font-medium text-sm">Ganti Icon</p>
                             </div>
                        </div>
                     </div>
                </div>

                <!-- Color Theme -->
                <div class="space-y-4">
                     <label class="text-sm font-semibold text-slate-700">Tema Warna <span class="text-red-500">*</span></label>
                     <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                         @foreach(\App\Models\WhyChooseUs::getThemes() as $key => $label)
                         @php $safeKey = ($key === 'primary') ? 'emerald' : $key; @endphp
                         <label class="cursor-pointer group">
                             <input type="radio" name="color_theme" value="{{ $key }}" class="peer sr-only" {{ old('color_theme', $whyChooseUs->color_theme) == $key ? 'checked' : '' }}>
                             <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 peer-checked:border-{{ $safeKey }}-500 peer-checked:bg-{{ $safeKey }}-50/50 peer-checked:ring-1 peer-checked:ring-{{ $safeKey }}-500 transition-all hover:bg-slate-50 h-full">
                                 <div class="w-8 h-8 rounded-full bg-{{ $safeKey }}-500 shadow-sm flex-shrink-0 border-2 border-white ring-1 ring-slate-100"></div>
                                 <span class="text-xs font-semibold text-slate-600 group-hover:text-slate-900">{{ $label }}</span>
                             </div>
                         </label>
                         @endforeach
                     </div>
                </div>
            </div>

            <!-- Active Toggle -->
             <div class="pt-6 mt-6 border-t border-slate-50">
                 <label class="flex items-center gap-4 cursor-pointer group p-3 rounded-xl border border-transparent hover:border-indigo-100 hover:bg-indigo-50/30 transition-all">
                    <div class="relative flex-shrink-0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $whyChooseUs->is_active) ? 'checked' : '' }}>
                        <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500 after:shadow-sm"></div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-slate-800 block group-hover:text-indigo-700 transition-colors">Aktifkan Item</span>
                        <span class="text-xs text-slate-500">Item akan ditampilkan di halaman depan jika aktif.</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4">
            <a href="{{ route('admin.why-choose-us.index') }}" class="px-6 py-3 bg-white text-slate-600 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all text-center shadow-sm">Batal</a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-violet-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Perbarui Item
            </button>
        </div>
    </form>
</div>
@endsection
