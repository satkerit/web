@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<x-admin.page-header title="Kelola Produk" subtitle="Kelola produk dan layanan BPRS Babel">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.products.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Produk
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    {{-- Filter Section --}}
    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
                       class="w-full pl-10 rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6">
            </div>
            <div class="flex gap-3">
                <select name="type" class="flex-1 sm:flex-none rounded-xl border-0 py-2.5 px-4 pr-10 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6">
                    <option value="">Semua Tipe</option>
                    <option value="simpanan_syariah" {{ request('type') == 'simpanan_syariah' ? 'selected' : '' }}>Simpanan Syariah</option>
                    <option value="pembiayaan_syariah" {{ request('type') == 'pembiayaan_syariah' ? 'selected' : '' }}>Pembiayaan Syariah</option>
                    <option value="deposito_syariah" {{ request('type') == 'deposito_syariah' ? 'selected' : '' }}>Deposito Syariah</option>
                </select>
                <x-admin.button type="submit" variant="secondary">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </x-admin.button>
                @if(request('search') || request('type'))
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 bg-white rounded-xl ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($products as $product)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm ring-1 ring-slate-900/5 hover:shadow-md transition-shadow">
                {{-- Product Image --}}
                <div class="relative h-40 bg-slate-100">
                    @if($product->image)
                        <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}" alt="{{ $product->image_alt ?? $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    {{-- Status Badge --}}
                    <div class="absolute top-3 right-3">
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-bold text-slate-900 line-clamp-1">{{ $product->name }}</h3>
                        <span class="text-xs text-slate-400 font-medium whitespace-nowrap">#{{ $product->order_position ?? '-' }}</span>
                    </div>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-3">{{ $product->short_description ?: 'Tidak ada deskripsi' }}</p>

                    <div class="flex items-center gap-2 mb-4">
                        <x-admin.badge variant="info">
                            @switch($product->type)
                                @case('simpanan_syariah') Simpanan Syariah @break
                                @case('pembiayaan_syariah') Pembiayaan Syariah @break
                                @case('deposito_syariah') Deposito Syariah @break
                                @default {{ ucfirst(str_replace('_', ' ', $product->type)) }}
                            @endswitch
                        </x-admin.badge>
                        @if($product->interest_rate)
                            <span class="text-xs text-slate-500">{{ $product->interest_rate }}</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-blue-600 bg-green-50 hover:bg-blue-100 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <button type="button" onclick="confirmDelete('{{ $product->slug }}', '{{ $product->name }}')" class="flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-1">Belum Ada Produk</h3>
                <p class="text-slate-500 mb-4">Mulai tambahkan produk pertama Anda</p>
                <x-admin.button href="{{ route('admin.products.create') }}" size="sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Produk
                </x-admin.button>
            </div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Produk', 'Tipe', 'Nisbah/Margin', 'Status', 'Urutan', 'Aksi']">
            @forelse($products as $product)
                <tr class="group hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($product->image)
                                <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}" alt="{{ $product->image_alt ?? $product->name }}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0 bg-slate-100 ring-1 ring-slate-200">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-100 ring-1 ring-slate-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900 truncate max-w-[250px]">{{ $product->name }}</p>
                                <p class="text-sm text-slate-500 truncate max-w-[250px] mt-0.5">{{ Str::limit($product->short_description, 60) ?: '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-admin.badge variant="info">
                            @switch($product->type)
                                @case('simpanan_syariah') Simpanan Syariah @break
                                @case('pembiayaan_syariah') Pembiayaan Syariah @break
                                @case('deposito_syariah') Deposito Syariah @break
                                @default {{ ucfirst(str_replace('_', ' ', $product->type)) }}
                            @endswitch
                        </x-admin.badge>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                        {{ $product->interest_rate ?: '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-sm font-semibold text-slate-600">
                            {{ $product->order_position ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.products.show', $product) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-green-50 rounded-lg transition-all" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button type="button" onclick="confirmDelete('{{ $product->slug }}', '{{ $product->name }}')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada produk</p>
                        <p class="text-sm text-slate-400 mt-1">Klik tombol "Tambah Produk" untuk menambahkan</p>
                    </td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $products->links() }}
        </div>
    @endif
</x-admin.card>

{{-- Delete Confirmation Modal --}}
<x-admin.delete-modal
    id="deleteModal"
    title="Hapus Produk"
    message="Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan."
/>

@push('scripts')
<script>
    function confirmDelete(slug, name) {
        const modal = document.getElementById('deleteModal');
        const form = modal.querySelector('form');
        const messageEl = modal.querySelector('[data-message]');

        form.action = `{{ url('admin/products') }}/${slug}`;
        if (messageEl) {
            messageEl.textContent = `Apakah Anda yakin ingin menghapus produk "${name}"? Tindakan ini tidak dapat dibatalkan.`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
@endpush
@endsection

