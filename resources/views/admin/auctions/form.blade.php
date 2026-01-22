@extends('layouts.admin')

@section('title', isset($auction) ? 'Edit Lelang' : 'Tambah Lelang')

@section('content')
<x-admin.page-header :title="isset($auction) ? 'Edit Lelang' : 'Tambah Lelang'" :subtitle="isset($auction) ? $auction->title : 'Buat informasi lelang baru'">
    <x-slot:actions>
        @if(isset($auction))
            <a href="{{ route('auctions.show', $auction->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 bg-white rounded-xl ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat di Website
            </a>
        @endif
        <x-admin.button href="{{ route('admin.auctions.index') }}" variant="secondary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<form action="{{ isset($auction) ? route('admin.auctions.update', $auction) : route('admin.auctions.store') }}" method="POST" enctype="multipart/form-data" x-data="auctionForm('{{ old('status', $auction->status ?? 'upcoming') }}')">
    @csrf
    @if(isset($auction)) @method('PUT') @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Informasi Dasar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <x-admin.input name="title" label="Judul Lelang" :value="old('title', $auction->title ?? '')" required/>
                    </div>
                    <x-admin.input name="object_number" label="Nomor Objek" :value="old('object_number', $auction->object_number ?? '')"/>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Aset *</label>
                        <select name="asset_type" required class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">
                            @foreach(\App\Models\Auction::$assetTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('asset_type', $auction->asset_type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Sertifikat</label>
                        <select name="certificate_type" class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">
                            <option value="">Pilih</option>
                            @foreach(\App\Models\Auction::$certificateTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('certificate_type', $auction->certificate_type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-admin.input name="certificate_number" label="Nomor Sertifikat" :value="old('certificate_number', $auction->certificate_number ?? '')"/>
                    <x-admin.input type="number" name="land_area" label="Luas Tanah (m2)" :value="old('land_area', $auction->land_area ?? '')" step="0.01"/>
                    <x-admin.input type="number" name="building_area" label="Luas Bangunan (m2)" :value="old('building_area', $auction->building_area ?? '')" step="0.01"/>
                    <x-admin.input name="debtor_name" label="Nama Debitur" :value="old('debtor_name', $auction->debtor_name ?? '')"/>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi *</label>
                        <textarea name="location" rows="2" required class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">{{ old('location', $auction->location ?? '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="4" class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">{{ old('description', $auction->description ?? '') }}</textarea>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Informasi Harga & Lelang">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.input type="number" name="starting_price" label="Harga Limit" :value="old('starting_price', $auction->starting_price ?? '')" required step="1"/>
                    <x-admin.input type="number" name="estimated_price" label="Nilai Estimasi" :value="old('estimated_price', $auction->estimated_price ?? '')" step="1"/>
                    <x-admin.input type="datetime-local" name="auction_date" label="Tanggal Lelang" :value="old('auction_date', isset($auction) && $auction->auction_date ? $auction->auction_date->format('Y-m-d\TH:i') : '')" required/>
                    <x-admin.input type="datetime-local" name="registration_deadline" label="Batas Pendaftaran" :value="old('registration_deadline', isset($auction) && $auction->registration_deadline ? $auction->registration_deadline->format('Y-m-d\TH:i') : '')"/>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Lelang *</label>
                        <select name="auction_type" required class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">
                            @foreach(\App\Models\Auction::$auctionTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('auction_type', $auction->auction_type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-admin.input name="auction_location" label="Tempat Lelang" :value="old('auction_location', $auction->auction_location ?? '')"/>
                    <x-admin.input type="number" name="deposit_amount" label="Uang Jaminan (Rp)" :value="old('deposit_amount', $auction->deposit_amount ?? '')" step="1"/>
                    <x-admin.input type="number" name="deposit_percentage" label="Persentase Jaminan (%)" :value="old('deposit_percentage', $auction->deposit_percentage ?? '')" step="0.01" max="100"/>
                    <x-admin.input name="kpknl_office" label="Kantor KPKNL" :value="old('kpknl_office', $auction->kpknl_office ?? '')"/>
                    <x-admin.input name="risalah_number" label="Nomor Risalah" :value="old('risalah_number', $auction->risalah_number ?? '')"/>
                </div>
            </x-admin.card>

            <x-admin.card title="Informasi Bank & Kontak">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.input name="bank_name" label="Nama Bank" :value="old('bank_name', $auction->bank_name ?? '')"/>
                    <x-admin.input name="bank_account" label="Nomor Rekening" :value="old('bank_account', $auction->bank_account ?? '')"/>
                    <x-admin.input name="account_holder" label="Atas Nama" :value="old('account_holder', $auction->account_holder ?? '')"/>
                    <x-admin.input name="contact_person" label="Contact Person" :value="old('contact_person', $auction->contact_person ?? '')"/>
                    <x-admin.input name="contact_phone" label="No. Telepon" :value="old('contact_phone', $auction->contact_phone ?? '')"/>
                </div>
            </x-admin.card>

            <x-admin.card title="Informasi Tambahan">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jadwal Viewing</label>
                        <textarea name="viewing_schedule" rows="2" class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">{{ old('viewing_schedule', $auction->viewing_schedule ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Syarat & Ketentuan</label>
                        <textarea name="terms_conditions" rows="4" class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">{{ old('terms_conditions', $auction->terms_conditions ?? '') }}</textarea>
                    </div>
                    <x-admin.input name="meta_description" label="Meta Description" :value="old('meta_description', $auction->meta_description ?? '')"/>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Status Lelang">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status *</label>
                        <select name="status" x-model="status" required class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 sm:text-sm">
                            @foreach(\App\Models\Auction::$statusLabels as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $auction->status ?? 'upcoming') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="status === 'sold'" x-transition class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 space-y-4">
                        <p class="text-emerald-700 font-semibold text-sm">Info Penjualan</p>
                        <div>
                            <label class="block text-sm font-medium text-emerald-700 mb-1">Harga Terjual</label>
                            <input type="number" name="winning_bid" value="{{ old('winning_bid', $auction->winning_bid ?? '') }}" step="1" class="block w-full rounded-lg border-0 py-2 px-3 text-slate-900 bg-white shadow-sm ring-1 ring-emerald-300 focus:ring-2 focus:ring-emerald-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-emerald-700 mb-1">Nama Pemenang</label>
                            <input type="text" name="winner_name" value="{{ old('winner_name', $auction->winner_name ?? '') }}" class="block w-full rounded-lg border-0 py-2 px-3 text-slate-900 bg-white shadow-sm ring-1 ring-emerald-300 focus:ring-2 focus:ring-emerald-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-emerald-700 mb-1">Tanggal Terjual</label>
                            <input type="datetime-local" name="sold_at" value="{{ old('sold_at', isset($auction) && $auction->sold_at ? $auction->sold_at->format('Y-m-d\TH:i') : '') }}" class="block w-full rounded-lg border-0 py-2 px-3 text-slate-900 bg-white shadow-sm ring-1 ring-emerald-300 focus:ring-2 focus:ring-emerald-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Gambar">
                <div class="space-y-3">
                    @if(isset($auction) && $auction->images)
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($auction->images as $image)
                                <img src="{{ \App\Helpers\StorageHelper::url($image) }}" alt="" class="w-full h-20 object-cover rounded-lg">
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="images[]" accept="image/*" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-slate-500">Multiple gambar. Maks 2MB/file.</p>
                </div>
            </x-admin.card>

            <x-admin.card title="Dokumen">
                <div class="space-y-3">
                    @if(isset($auction) && $auction->documents)
                        <ul class="text-sm space-y-1">
                            @foreach($auction->documents as $doc)
                                <li class="text-slate-600">📄 {{ $doc['name'] ?? 'Document' }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <input type="file" name="documents[]" accept=".pdf" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-slate-500">PDF. Maks 10MB/file.</p>
                </div>
            </x-admin.card>

            <x-admin.button type="submit" class="w-full">
                {{ isset($auction) ? 'Simpan Perubahan' : 'Tambah Lelang' }}
            </x-admin.button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
@endpush
