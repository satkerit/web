@extends('layouts.admin')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<x-admin.page-header title="Detail Produk" :subtitle="$product->name">
    <x-slot:actions>
        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 bg-white rounded-xl ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat di Website
        </a>
        <x-admin.button href="{{ route('admin.products.edit', $product) }}">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Produk
        </x-admin.button>
        <x-admin.button href="{{ route('admin.products.index') }}" variant="secondary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Product Header --}}
        <x-admin.card>
            <div class="flex flex-col sm:flex-row gap-6">
                {{-- Product Image --}}
                <div class="flex-shrink-0">
                    @if($product->image)
                        <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}" alt="{{ $product->image_alt ?? $product->name }}" class="w-full sm:w-48 h-48 rounded-xl object-cover ring-1 ring-slate-200">
                    @else
                        <div class="w-full sm:w-48 h-48 rounded-xl bg-slate-100 flex items-center justify-center ring-1 ring-slate-200">
                            <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Product Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <x-admin.badge variant="info">
                            @switch($product->type)
                                @case('simpanan_syariah') Simpanan Syariah @break
                                @case('pembiayaan_syariah') Pembiayaan Syariah @break
                                @case('deposito_syariah') Deposito Syariah @break
                                @default {{ ucfirst(str_replace('_', ' ', $product->type)) }}
                            @endswitch
                        </x-admin.badge>
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $product->name }}</h2>

                    @if($product->short_description)
                        <p class="text-slate-600 mb-4">{{ $product->short_description }}</p>
                    @endif

                    @if($product->interest_rate)
                        <div class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-semibold text-emerald-700">Nisbah/Margin: {{ $product->interest_rate }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </x-admin.card>

        {{-- Description --}}
        <x-admin.card title="Deskripsi Lengkap">
            <div class="prose prose-slate max-w-none">
                {!! nl2br(e($product->description)) !!}
            </div>
        </x-admin.card>

        {{-- Features --}}
        @if($product->features && count($product->features) > 0)
            <x-admin.card title="Fitur Produk">
                <ul class="space-y-3">
                    @foreach($product->features as $feature)
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="text-slate-700">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-admin.card>
        @endif

        {{-- Benefits --}}
        @if($product->benefits && count($product->benefits) > 0)
            <x-admin.card title="Keunggulan Produk">
                <ul class="space-y-3">
                    @foreach($product->benefits as $benefit)
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </span>
                            <span class="text-slate-700">{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-admin.card>
        @endif

        {{-- Requirements --}}
        @if($product->requirements && count($product->requirements) > 0)
            <x-admin.card title="Persyaratan">
                <ul class="space-y-3">
                    @foreach($product->requirements as $requirement)
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            <span class="text-slate-700">{{ $requirement }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-admin.card>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Product Meta --}}
        <x-admin.card title="Informasi Produk">
            <dl class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <dt class="text-sm text-slate-500">ID</dt>
                    <dd class="text-sm font-medium text-slate-900">#{{ $product->id }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <dt class="text-sm text-slate-500">Slug</dt>
                    <dd class="text-sm font-mono text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $product->slug }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <dt class="text-sm text-slate-500">Urutan</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $product->order_position ?? '-' }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <dt class="text-sm text-slate-500">Dibuat</dt>
                    <dd class="text-sm text-slate-900">{{ $product->created_at->format('d M Y, H:i') }}</dd>
                </div>
                <div class="flex justify-between items-center py-2">
                    <dt class="text-sm text-slate-500">Diperbarui</dt>
                    <dd class="text-sm text-slate-900">{{ $product->updated_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </x-admin.card>

        {{-- Image Info --}}
        @if($product->image)
            <x-admin.card title="Gambar">
                <div class="space-y-3">
                    <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}" alt="{{ $product->image_alt ?? $product->name }}" class="w-full rounded-lg object-cover ring-1 ring-slate-200">
                    @if($product->image_alt)
                        <p class="text-xs text-slate-500">
                            <span class="font-medium">Alt Text:</span> {{ $product->image_alt }}
                        </p>
                    @endif
                    <p class="text-xs text-slate-500">
                        <span class="font-medium">Path:</span>
                        <code class="bg-slate-100 px-1 py-0.5 rounded text-xs">{{ $product->image }}</code>
                    </p>
                </div>
            </x-admin.card>
        @endif

        {{-- Quick Actions --}}
        <x-admin.card title="Aksi Cepat">
            <div class="space-y-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Produk
                </a>
                <button type="button" onclick="confirmDelete()" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Produk
                </button>
            </div>
        </x-admin.card>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<x-admin.delete-modal
    id="deleteModal"
    title="Hapus Produk"
    :message="'Apakah Anda yakin ingin menghapus produk \'' . $product->name . '\'? Tindakan ini tidak dapat dibatalkan.'"
/>

@push('scripts')
<script>
    function confirmDelete() {
        const modal = document.getElementById('deleteModal');
        const form = modal.querySelector('form');

        form.action = '{{ route('admin.products.destroy', $product) }}';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
@endpush
@endsection

