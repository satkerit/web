@extends('layouts.admin')

@section('title', 'Profil Perusahaan')

@section('content')
<x-admin.page-header title="Profil Perusahaan" subtitle="Kelola informasi dan profil perusahaan">
</x-admin.page-header>

@if (session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
        <div class="flex items-center gap-2 font-semibold mb-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Terdapat kesalahan pada form:
        </div>
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.company-info.update') }}" method="POST" enctype="multipart/form-data" 
      x-data="companyInfoForm()">
    @csrf
    @method('PUT')

    <div class="space-y-6">
        <!-- Basic Information -->
        <x-admin.card title="Informasi Dasar" subtitle="Data utama perusahaan">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Perusahaan *</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $company->name) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           required>
                </div>

                <div>
                    <label for="tagline" class="block text-sm font-medium text-slate-700 mb-2">Tagline</label>
                    <input type="text" name="tagline" id="tagline" 
                           value="{{ old('tagline', $company->tagline) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="established_year" class="block text-sm font-medium text-slate-700 mb-2">Tahun Berdiri</label>
                    <input type="number" name="established_year" id="established_year" 
                           value="{{ old('established_year', $company->established_year) }}" 
                           min="1900" max="{{ date('Y') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Deskripsi Perusahaan</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('description', $company->description) }}</textarea>
                </div>
            </div>
        </x-admin.card>

        <!-- Contact Information -->
        <x-admin.card title="Informasi Kontak" subtitle="Data kontak dan alamat perusahaan">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-2">Alamat</label>
                    <textarea name="address" id="address" rows="3" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('address', $company->address) }}</textarea>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Telepon</label>
                    <input type="text" name="phone" id="phone" 
                           value="{{ old('phone', $company->phone) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="fax" class="block text-sm font-medium text-slate-700 mb-2">Fax</label>
                    <input type="text" name="fax" id="fax" 
                           value="{{ old('fax', $company->fax) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-slate-700 mb-2">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" 
                           value="{{ old('whatsapp', $company->whatsapp) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="website" class="block text-sm font-medium text-slate-700 mb-2">Website</label>
                    <input type="url" name="website" id="website" 
                           value="{{ old('website', $company->website) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Utama</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $company->email) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="email_contact" class="block text-sm font-medium text-slate-700 mb-2">Email Kontak</label>
                    <input type="email" name="email_contact" id="email_contact" 
                           value="{{ old('email_contact', $company->email_contact) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="email_complaint" class="block text-sm font-medium text-slate-700 mb-2">Email Pengaduan</label>
                    <input type="email" name="email_complaint" id="email_complaint" 
                           value="{{ old('email_complaint', $company->email_complaint) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="email_whistleblowing" class="block text-sm font-medium text-slate-700 mb-2">Email Whistleblowing</label>
                    <input type="email" name="email_whistleblowing" id="email_whistleblowing" 
                           value="{{ old('email_whistleblowing', $company->email_whistleblowing) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>
        </x-admin.card>

        <!-- Visual Assets -->
        <x-admin.card title="Aset Visual" subtitle="Logo, favicon, dan gambar perusahaan">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Logo -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Logo Utama</label>
                    <x-file-upload 
                        name="logo" 
                        :current="$company->logo_url"
                        accept="image/*"
                        max-size="2048"
                        help="Format: JPG, PNG, SVG. Maksimal 2MB" />
                </div>

                <!-- Logo Footer -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Logo Footer</label>
                    <x-file-upload 
                        name="logo_footer" 
                        :current="$company->logo_footer_url"
                        accept="image/*"
                        max-size="2048"
                        help="Format: JPG, PNG, SVG. Maksimal 2MB" />
                    
                    <div class="mt-4 space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="logo_footer_remove_bg" value="1" 
                                   {{ old('logo_footer_remove_bg', $company->logo_footer_remove_bg) ? 'checked' : '' }}
                                   class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-slate-600">Remove Background</span>
                        </label>
                        
                        <div x-data="{ value: {{ old('logo_footer_opacity', $company->logo_footer_opacity ?? 100) }} }">
                            <label for="logo_footer_opacity" class="block text-sm text-slate-600 mb-1">Opacity (%)</label>
                            <input type="range" name="logo_footer_opacity" id="logo_footer_opacity" 
                                   min="0" max="100" 
                                   x-model="value"
                                   class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                            <span x-text="value + '%'" class="text-sm text-slate-500"></span>
                        </div>
                    </div>
                </div>

                <!-- Favicon -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Favicon</label>
                    <x-file-upload 
                        name="favicon" 
                        :current="$company->favicon_url"
                        accept=".ico,.png,.jpg,.jpeg"
                        max-size="512"
                        help="Format: ICO, PNG. Maksimal 512KB" />
                </div>

                <!-- Profile Image -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Profil</label>
                    <x-file-upload 
                        name="profile_image" 
                        :current="$company->profile_image_url"
                        accept="image/*"
                        max-size="5120"
                        help="Format: JPG, PNG, WebP. Maksimal 5MB" />
                </div>

                <!-- Organization Structure -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Struktur Organisasi</label>
                    <x-file-upload 
                        name="organization_structure" 
                        :current="$company->organization_structure_url"
                        accept="image/*"
                        max-size="5120"
                        help="Format: JPG, PNG, WebP. Maksimal 5MB" />
                </div>
            </div>
        </x-admin.card>

        <!-- Company Profile -->
        <x-admin.card title="Profil Perusahaan" subtitle="Visi, misi, dan sejarah perusahaan">
            <div class="space-y-6">
                <div>
                    <label for="vision" class="block text-sm font-medium text-slate-700 mb-2">Visi</label>
                    <textarea name="vision" id="vision" rows="3" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('vision', $company->vision) }}</textarea>
                </div>

                <div>
                    <label for="mission" class="block text-sm font-medium text-slate-700 mb-2">Misi</label>
                    <textarea name="mission" id="mission" rows="4" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('mission', $company->mission) }}</textarea>
                </div>

                <div>
                    <label for="history" class="block text-sm font-medium text-slate-700 mb-2">Sejarah</label>
                    <textarea name="history" id="history" rows="5" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('history', $company->history) }}</textarea>
                </div>
            </div>
        </x-admin.card>

        <!-- Statistics -->
        <x-admin.card title="Statistik" subtitle="Data statistik perusahaan">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="stat_years_experience" class="block text-sm font-medium text-slate-700 mb-2">Tahun Pengalaman</label>
                    <input type="number" name="stat_years_experience" id="stat_years_experience" 
                           value="{{ old('stat_years_experience', $company->stat_years_experience) }}" 
                           min="0" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="stat_branch_offices" class="block text-sm font-medium text-slate-700 mb-2">Kantor Cabang</label>
                    <input type="number" name="stat_branch_offices" id="stat_branch_offices" 
                           value="{{ old('stat_branch_offices', $company->stat_branch_offices) }}" 
                           min="0" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="stat_total_assets" class="block text-sm font-medium text-slate-700 mb-2">Total Aset</label>
                    <input type="text" name="stat_total_assets" id="stat_total_assets" 
                           value="{{ old('stat_total_assets', $company->stat_total_assets) }}" 
                           placeholder="contoh: 150 Miliar"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="stat_cash_offices" class="block text-sm font-medium text-slate-700 mb-2">Kantor Kas</label>
                    <input type="number" name="stat_cash_offices" id="stat_cash_offices" 
                           value="{{ old('stat_cash_offices', $company->stat_cash_offices) }}" 
                           min="0" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="stat_mobile_cash_offices" class="block text-sm font-medium text-slate-700 mb-2">Kas Keliling</label>
                    <input type="number" name="stat_mobile_cash_offices" id="stat_mobile_cash_offices" 
                           value="{{ old('stat_mobile_cash_offices', $company->stat_mobile_cash_offices) }}" 
                           min="0" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="legacy_visitor_count" class="block text-sm font-medium text-slate-700 mb-2">Jumlah Pengunjung Legacy</label>
                    <input type="number" name="legacy_visitor_count" id="legacy_visitor_count" 
                           value="{{ old('legacy_visitor_count', $company->legacy_visitor_count) }}" 
                           min="0" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>
        </x-admin.card>

        <!-- Social Media -->
        <x-admin.card title="Media Sosial" subtitle="Link media sosial perusahaan">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="facebook" class="block text-sm font-medium text-slate-700 mb-2">Facebook</label>
                    <input type="url" name="facebook" id="facebook" 
                           value="{{ old('facebook', $company->facebook) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="instagram" class="block text-sm font-medium text-slate-700 mb-2">Instagram</label>
                    <input type="url" name="instagram" id="instagram" 
                           value="{{ old('instagram', $company->instagram) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="twitter" class="block text-sm font-medium text-slate-700 mb-2">Twitter</label>
                    <input type="url" name="twitter" id="twitter" 
                           value="{{ old('twitter', $company->twitter) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="youtube" class="block text-sm font-medium text-slate-700 mb-2">YouTube</label>
                    <input type="url" name="youtube" id="youtube" 
                           value="{{ old('youtube', $company->youtube) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="linkedin" class="block text-sm font-medium text-slate-700 mb-2">LinkedIn</label>
                    <input type="url" name="linkedin" id="linkedin" 
                           value="{{ old('linkedin', $company->linkedin) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="tiktok" class="block text-sm font-medium text-slate-700 mb-2">TikTok</label>
                    <input type="url" name="tiktok" id="tiktok" 
                           value="{{ old('tiktok', $company->tiktok) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>
        </x-admin.card>

        <!-- Regulatory Information -->
        <x-admin.card title="Informasi Regulasi" subtitle="Data regulasi dan lisensi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="ojk_license" class="block text-sm font-medium text-slate-700 mb-2">Nomor Izin OJK</label>
                    <input type="text" name="ojk_license" id="ojk_license" 
                           value="{{ old('ojk_license', $company->ojk_license) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="lps_guarantee_amount" class="block text-sm font-medium text-slate-700 mb-2">Jumlah Jaminan LPS</label>
                    <input type="text" name="lps_guarantee_amount" id="lps_guarantee_amount" 
                           value="{{ old('lps_guarantee_amount', $company->lps_guarantee_amount) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="ojk_tagline" class="block text-sm font-medium text-slate-700 mb-2">Tagline OJK</label>
                    <textarea name="ojk_tagline" id="ojk_tagline" rows="2" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('ojk_tagline', $company->ojk_tagline) }}</textarea>
                </div>

                <div>
                    <label for="lps_tagline" class="block text-sm font-medium text-slate-700 mb-2">Tagline LPS</label>
                    <textarea name="lps_tagline" id="lps_tagline" rows="2" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('lps_tagline', $company->lps_tagline) }}</textarea>
                </div>
            </div>
        </x-admin.card>

        <!-- SEO & Footer -->
        <x-admin.card title="SEO & Footer" subtitle="Pengaturan SEO dan footer website">
            <div class="space-y-6">
                <div>
                    <label for="footer_description" class="block text-sm font-medium text-slate-700 mb-2">Deskripsi Footer</label>
                    <textarea name="footer_description" id="footer_description" rows="3" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('footer_description', $company->footer_description) }}</textarea>
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="2" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('meta_description', $company->meta_description) }}</textarea>
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-slate-700 mb-2">Meta Keywords</label>
                    <textarea name="meta_keywords" id="meta_keywords" rows="2" 
                              placeholder="Pisahkan dengan koma"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('meta_keywords', $company->meta_keywords) }}</textarea>
                </div>
            </div>
        </x-admin.card>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <x-admin.button type="submit" variant="primary" size="lg">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </x-admin.button>
        </div>
    </div>
</form>
@endsection