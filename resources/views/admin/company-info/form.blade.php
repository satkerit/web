@extends('layouts.admin')

@section('title', 'Info Perusahaan')

@section('content')
<x-admin.page-header title="Info Perusahaan" subtitle="Kelola informasi umum perusahaan"/>

@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
    <p class="text-sm font-medium text-red-800 mb-2">Terjadi kesalahan:</p>
    <ul class="list-disc list-inside text-sm text-red-700">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.company-info.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="space-y-6">
        {{-- Informasi Dasar --}}
        <x-admin.card title="Informasi Dasar">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="name" label="Nama Perusahaan" :value="old('name', $company->name ?? '')" required/>
                <x-admin.input name="tagline" label="Tagline" :value="old('tagline', $company->tagline ?? '')"/>
                <x-admin.input type="number" name="established_year" label="Tahun Berdiri" :value="old('established_year', $company->established_year ?? '')" min="1900"/>
                <x-admin.input type="url" name="website" label="Website" :value="old('website', $company->website ?? '')"/>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('description', $company->description ?? '') }}</textarea>
            </div>
        </x-admin.card>

        {{-- Logo & Favicon --}}
        <x-admin.card title="Logo & Favicon">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-admin.image-picker
                    name="logo"
                    label="Logo Header"
                    :value="$company->logo ?? null"
                    hint="Logo untuk header/navbar. Format: JPG, PNG, WEBP, SVG (maks 2MB)"
                    previewClass="h-16"
                />
                <x-admin.image-picker
                    name="logo_footer"
                    label="Logo Footer"
                    :value="$company->logo_footer ?? null"
                    hint="Logo untuk footer. Format: JPG, PNG, WEBP, SVG (maks 2MB)"
                    previewClass="h-16"
                />
                <x-admin.image-picker
                    name="favicon"
                    label="Favicon"
                    :value="$company->favicon ?? null"
                    accept=".ico,.png,.jpg,.jpeg"
                    hint="Format: ICO, PNG, JPG (maks 512KB)"
                    previewClass="h-8"
                />
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="hidden" name="logo_footer_remove_bg" value="0">
                        <input type="checkbox" name="logo_footer_remove_bg" value="1" {{ old('logo_footer_remove_bg', $company->logo_footer_remove_bg ?? false) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2">
                        <span class="ml-3 text-sm font-medium text-gray-700">Hapus background putih pada logo footer</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500 ml-8">Aktifkan jika logo memiliki background putih yang ingin dihilangkan</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transparansi Logo Footer</label>
                    <div class="flex items-center gap-3">
                        <input type="range" name="logo_footer_opacity" min="0" max="100" value="{{ old('logo_footer_opacity', $company->logo_footer_opacity ?? 100) }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-600" oninput="document.getElementById('opacity-value').textContent = this.value + '%'">
                        <span id="opacity-value" class="text-sm font-medium text-gray-700 w-12">{{ old('logo_footer_opacity', $company->logo_footer_opacity ?? 100) }}%</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Atur tingkat transparansi logo (0% = transparan penuh, 100% = tidak transparan)</p>
                </div>
            </div>
        </x-admin.card>

        {{-- Kontak --}}
        <x-admin.card title="Kontak">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-admin.input name="phone" label="Telepon" :value="old('phone', $company->phone ?? '')"/>
                <x-admin.input name="fax" label="Fax" :value="old('fax', $company->fax ?? '')"/>
                <x-admin.input name="whatsapp" label="WhatsApp" :value="old('whatsapp', $company->whatsapp ?? '')"/>
                <x-admin.input type="email" name="email" label="Email Utama" :value="old('email', $company->email ?? '')"/>
                <x-admin.input type="email" name="email_contact" label="Email Kontak" :value="old('email_contact', $company->email_contact ?? '')"/>
                <x-admin.input type="email" name="email_complaint" label="Email Pengaduan" :value="old('email_complaint', $company->email_complaint ?? '')"/>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('address', $company->address ?? '') }}</textarea>
            </div>
        </x-admin.card>

        {{-- Visi & Misi --}}
        <x-admin.card title="Visi & Misi">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                    <textarea name="vision" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('vision', $company->vision ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                    <textarea name="mission" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('mission', $company->mission ?? '') }}</textarea>
                </div>
            </div>
        </x-admin.card>

        {{-- Sejarah Perusahaan --}}
        <x-admin.card title="Sejarah Perusahaan">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sejarah</label>
                <textarea name="history" rows="8" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Tuliskan sejarah perusahaan...">{{ old('history', $company->history ?? '') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Ceritakan perjalanan dan sejarah perusahaan. Akan ditampilkan di halaman Profil Perusahaan.</p>
            </div>
        </x-admin.card>

        {{-- Struktur Organisasi --}}
        <x-admin.card title="Struktur Organisasi">
            <x-admin.image-picker
                name="organization_structure"
                label="Gambar Struktur Organisasi"
                :value="$company->organization_structure ?? null"
                hint="Format: JPG, PNG, WEBP (maks 5MB). Disarankan resolusi tinggi untuk tampilan yang jelas."
                previewClass="w-full max-h-96 object-contain"
            />
            @if($company->organization_structure)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">File saat ini:</span>
                    <a href="{{ Storage::url($company->organization_structure) }}" target="_blank" class="text-emerald-600 hover:underline ml-1">
                        Lihat gambar →
                    </a>
                </p>
            </div>
            @endif
        </x-admin.card>

        {{-- Statistik --}}
        <x-admin.card title="Statistik">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-admin.input type="number" name="stat_years_experience" label="Tahun Pengalaman" :value="old('stat_years_experience', $company->stat_years_experience ?? '')"/>
                <x-admin.input type="number" name="stat_branch_offices" label="Jumlah Kantor Cabang" :value="old('stat_branch_offices', $company->stat_branch_offices ?? '')"/>
                <x-admin.input type="number" name="stat_cash_offices" label="Jumlah Kantor Kas" :value="old('stat_cash_offices', $company->stat_cash_offices ?? '')"/>
                <x-admin.input type="number" name="stat_mobile_cash_offices" label="Jumlah Kas Keliling" :value="old('stat_mobile_cash_offices', $company->stat_mobile_cash_offices ?? '')"/>
                <x-admin.input name="stat_total_assets" label="Total Aset" :value="old('stat_total_assets', $company->stat_total_assets ?? '')" placeholder="Contoh: 500 Miliar"/>
            </div>
        </x-admin.card>

        {{-- Social Media --}}
        <x-admin.card title="Media Sosial">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-admin.input type="url" name="facebook" label="Facebook" :value="old('facebook', $company->facebook ?? '')"/>
                <x-admin.input type="url" name="instagram" label="Instagram" :value="old('instagram', $company->instagram ?? '')"/>
                <x-admin.input type="url" name="twitter" label="Twitter/X" :value="old('twitter', $company->twitter ?? '')"/>
                <x-admin.input type="url" name="youtube" label="YouTube" :value="old('youtube', $company->youtube ?? '')"/>
                <x-admin.input type="url" name="linkedin" label="LinkedIn" :value="old('linkedin', $company->linkedin ?? '')"/>
                <x-admin.input type="url" name="tiktok" label="TikTok" :value="old('tiktok', $company->tiktok ?? '')"/>
            </div>
        </x-admin.card>

        {{-- Regulasi --}}
        <x-admin.card title="Informasi Regulasi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="ojk_license" label="Nomor Izin OJK" :value="old('ojk_license', $company->ojk_license ?? '')"/>
                <x-admin.input name="ojk_tagline" label="Tagline OJK" :value="old('ojk_tagline', $company->ojk_tagline ?? '')"/>
                <x-admin.input name="lps_tagline" label="Tagline LPS" :value="old('lps_tagline', $company->lps_tagline ?? '')"/>
                <x-admin.input name="lps_guarantee_amount" label="Jumlah Jaminan LPS" :value="old('lps_guarantee_amount', $company->lps_guarantee_amount ?? '')" placeholder="Contoh: 2 Miliar"/>
            </div>
        </x-admin.card>

        {{-- SEO --}}
        <x-admin.card title="SEO">
            <div class="space-y-4">
                <x-admin.input name="meta_description" label="Meta Description" :value="old('meta_description', $company->meta_description ?? '')" hint="Deskripsi untuk mesin pencari (maks 160 karakter)"/>
                <x-admin.input name="meta_keywords" label="Meta Keywords" :value="old('meta_keywords', $company->meta_keywords ?? '')" hint="Kata kunci dipisahkan koma"/>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Footer</label>
                    <textarea name="footer_description" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('footer_description', $company->footer_description ?? '') }}</textarea>
                </div>
            </div>
        </x-admin.card>

        <div class="flex justify-end">
            <x-admin.button type="submit">Simpan Perubahan</x-admin.button>
        </div>
    </div>
</form>
@endsection
