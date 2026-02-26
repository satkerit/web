@extends('layouts.admin')

@section('title', isset($report) ? 'Edit Laporan' : 'Tambah Laporan')

@section('content')
<x-admin.page-header :title="isset($report) ? 'Edit Laporan' : 'Tambah Laporan'">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.reports.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if ($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
    <h4 class="text-red-800 font-medium mb-2">Terjadi kesalahan:</h4>
    <ul class="list-disc list-inside text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
    <p class="text-red-700">{{ session('error') }}</p>
</div>
@endif

<form action="{{ isset($report) ? route('admin.reports.update', $report) : route('admin.reports.store') }}" method="POST" enctype="multipart/form-data" x-data="reportForm('{{ old('posting_mode', $report->posting_mode ?? 'auto') }}')">
    @csrf
    @if(isset($report)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Informasi Laporan">
                <div class="space-y-4">
                    <x-admin.input name="title" label="Judul Laporan" :value="old('title', $report->title ?? '')" required :error="$errors->first('title')"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Laporan <span class="text-red-500">*</span></label>
                            <select name="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('type') border-red-500 @enderror">
                                <option value="keuangan_publikasi" {{ old('type', $report->type ?? '') == 'keuangan_publikasi' ? 'selected' : '' }}>Laporan Keuangan Publikasi</option>
                                <option value="tata_kelola" {{ old('type', $report->type ?? '') == 'tata_kelola' ? 'selected' : '' }}>Laporan Tata Kelola</option>
                                <option value="tahunan" {{ old('type', $report->type ?? '') == 'tahunan' ? 'selected' : '' }}>Laporan Tahunan</option>
                                <option value="tahunan_berkelanjutan" {{ old('type', $report->type ?? '') == 'tahunan_berkelanjutan' ? 'selected' : '' }}>Laporan Tahunan Berkelanjutan</option>
                            </select>
                            @error('type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-admin.input type="number" name="year" label="Tahun" :value="old('year', $report->year ?? date('Y'))" required min="2000" :max="date('Y') + 1" :error="$errors->first('year')"/>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuartal</label>
                            <select name="quarter" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Tidak Ada (Tahunan)</option>
                                <option value="1" {{ old('quarter', $report->quarter ?? '') == '1' ? 'selected' : '' }}>Q1 (Januari - Maret)</option>
                                <option value="2" {{ old('quarter', $report->quarter ?? '') == '2' ? 'selected' : '' }}>Q2 (April - Juni)</option>
                                <option value="3" {{ old('quarter', $report->quarter ?? '') == '3' ? 'selected' : '' }}>Q3 (Juli - September)</option>
                                <option value="4" {{ old('quarter', $report->quarter ?? '') == '4' ? 'selected' : '' }}>Q4 (Oktober - Desember)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish <span class="text-red-500">*</span></label>
                            <input type="date" name="published_date"
                                   value="{{ old('published_date', isset($report) && $report->posted_at ? $report->posted_at->format('Y-m-d') : date('Y-m-d')) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('published_date') border-red-500 @enderror"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Tanggal yang akan ditampilkan di halaman publik</p>
                            @error('published_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mode Posting <span class="text-red-500">*</span></label>
                            <select name="posting_mode" x-model="postingMode" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('posting_mode') border-red-500 @enderror">
                                <option value="auto">Langsung Publish</option>
                                <option value="manual">Jadwalkan</option>
                            </select>
                            @error('posting_mode')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div x-show="postingMode === 'manual'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Tayang <span class="text-red-500" x-show="postingMode === 'manual'">*</span></label>
                            <input type="datetime-local" name="scheduled_at"
                                   value="{{ old('scheduled_at', isset($report) && $report->scheduled_at ? $report->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('scheduled_at') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Waktu laporan akan ditampilkan di website</p>
                            @error('scheduled_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('description') border-red-500 @enderror">{{ old('description', $report->description ?? '') }}</textarea>
                        @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Status">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                           {{ old('is_published', $report->is_published ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_published" class="text-sm text-gray-700">Publikasikan</label>
                </div>
            </x-admin.card>

            <x-admin.card title="File Laporan">
                <div class="space-y-3">
                    @if(isset($report) && $report->file_path)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">File saat ini:</p>
                            <a href="{{ \App\Helpers\StorageHelper::url($report->file_path) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                📄 Lihat File ({{ number_format($report->file_size / 1024 / 1024, 2) }} MB)
                            </a>
                        </div>
                    @endif
                    <input type="file" name="file" accept=".pdf" {{ isset($report) ? '' : 'required' }}
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500">Format PDF. Maks 50MB</p>
                    @error('file')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </x-admin.card>

            <x-admin.button type="submit" class="w-full">
                {{ isset($report) ? 'Simpan Perubahan' : 'Tambah Laporan' }}
            </x-admin.button>
        </div>
    </div>
</form>
@endsection
