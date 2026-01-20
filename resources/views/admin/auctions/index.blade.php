@extends('layouts.admin')

@section('title', 'Kelola Lelang')

@section('content')
<x-admin.page-header title="Kelola Lelang" subtitle="Kelola informasi lelang aset BPRS Babel">
    <x-slot:actions>
        @if(auth()->user()->hasPermission('auctions.create'))
        <x-admin.button href="{{ route('admin.auctions.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Lelang
        </x-admin.button>
        @endif
    </x-slot:actions>
</x-admin.page-header>

{{-- Statistics Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    @php
        $stats = [
            ['status' => 'upcoming', 'label' => 'Akan Datang', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'blue'],
            ['status' => 'ongoing', 'label' => 'Berlangsung', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
            ['status' => 'sold', 'label' => 'Terjual', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald'],
            ['status' => 'closed', 'label' => 'Selesai', 'icon' => 'M5 13l4 4L19 7', 'color' => 'slate'],
            ['status' => 'cancelled', 'label' => 'Dibatalkan', 'icon' => 'M6 18L18 6M6 6l12 12', 'color' => 'red'],
        ];
    @endphp
    @foreach($stats as $stat)
        <a href="{{ route('admin.auctions.index', ['status' => $stat['status']]) }}"
           class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-slate-900/5 hover:shadow-md transition-all group {{ request('status') == $stat['status'] ? 'ring-2 ring-'.$stat['color'].'-500' : '' }}">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-{{ $stat['color'] }}-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Auction::where('status', $stat['status'])->count() }}</p>
                    <p class="text-xs text-slate-500">{{ $stat['label'] }}</p>
                </div>
            </div>
        </a>
    @endforeach
</div>

<x-admin.card :noPadding="true">
    {{-- Filter Section --}}
    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau nomor objek..."
                       class="w-full pl-10 rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
            </div>
            <div class="flex flex-wrap gap-3">
                <select name="status" class="rounded-xl border-0 py-2.5 px-4 pr-10 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Auction::$statusLabels as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="asset_type" class="rounded-xl border-0 py-2.5 px-4 pr-10 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
                    <option value="">Semua Jenis Aset</option>
                    @foreach(\App\Models\Auction::$assetTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('asset_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <x-admin.button type="submit" variant="secondary">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </x-admin.button>
                @if(request('search') || request('status') || request('asset_type'))
                    <a href="{{ route('admin.auctions.index') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 bg-white rounded-xl ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
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
    <div class="block lg:hidden p-4 space-y-4">
        @forelse($auctions as $auction)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                {{-- Image --}}
                <div class="relative h-48 bg-slate-100">
                    @if($auction->images && count($auction->images) > 0)
                        <img src="{{ \App\Helpers\StorageHelper::url($auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-100 to-orange-100">
                            <svg class="w-16 h-16 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    {{-- Status Badge --}}
                    <div class="absolute top-3 left-3">
                        @php $colors = $auction->status_color; @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $colors['bg'] }} {{ $colors['text'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $colors['dot'] }} mr-1.5"></span>
                            {{ $auction->status_label }}
                        </span>
                    </div>
                    {{-- Sold Overlay --}}
                    @if($auction->status === 'sold')
                        <div class="absolute inset-0 bg-emerald-900/60 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-white font-bold text-lg">TERJUAL</span>
                                @if($auction->winning_bid)
                                    <p class="text-white/80 text-sm mt-1">Rp {{ number_format($auction->winning_bid, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-4">
                    <h3 class="font-bold text-slate-900 line-clamp-2 mb-2">{{ $auction->title }}</h3>
                    <p class="text-sm text-slate-500 mb-3">{{ $auction->object_number ?? 'No. Objek: -' }}</p>

                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-lg">
                            {{ \App\Models\Auction::$assetTypes[$auction->asset_type] ?? $auction->asset_type }}
                        </span>
                        <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg">
                            {{ \App\Models\Auction::$auctionTypes[$auction->auction_type] ?? $auction->auction_type }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-4 text-sm">
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-semibold text-amber-600">{{ $auction->formatted_starting_price }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $auction->auction_date?->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
                        @if(auth()->user()->hasPermission('auctions.edit'))
                        <a href="{{ route('admin.auctions.edit', $auction) }}" class="flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('auctions.delete'))
                        <button type="button" onclick="confirmDelete('{{ $auction->id }}', '{{ addslashes($auction->title) }}')" class="flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-1">Belum Ada Lelang</h3>
                <p class="text-slate-500 mb-4">Mulai tambahkan lelang pertama Anda</p>
                <x-admin.button href="{{ route('admin.auctions.create') }}" size="sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Lelang
                </x-admin.button>
            </div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block">
        <x-admin.table :headers="['Lelang', 'Jenis', 'Harga Limit', 'Tanggal Lelang', 'Status', 'Aksi']">
            @forelse($auctions as $auction)
                <tr class="group hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="relative flex-shrink-0">
                                @if($auction->images && count($auction->images) > 0)
                                    <img src="{{ \App\Helpers\StorageHelper::url($auction->images[0]) }}" alt="" class="w-16 h-16 rounded-xl object-cover ring-1 ring-slate-200">
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center ring-1 ring-slate-200">
                                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                @endif
                                @if($auction->status === 'sold')
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center ring-2 ring-white">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900 truncate max-w-[280px]">{{ $auction->title }}</p>
                                <p class="text-sm text-slate-500">{{ $auction->object_number ?? '-' }}</p>
                                @if($auction->status === 'sold' && $auction->winner_name)
                                    <p class="text-xs text-emerald-600 mt-1">Pemenang: {{ $auction->winner_name }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1">
                            <span class="inline-flex px-2 py-1 bg-slate-100 text-slate-700 text-xs font-medium rounded-lg">
                                {{ \App\Models\Auction::$assetTypes[$auction->asset_type] ?? $auction->asset_type }}
                            </span>
                            <p class="text-xs text-slate-500">{{ \App\Models\Auction::$auctionTypes[$auction->auction_type] ?? '' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-amber-600">{{ $auction->formatted_starting_price }}</p>
                            @if($auction->status === 'sold' && $auction->winning_bid)
                                <p class="text-xs text-emerald-600 mt-1">Terjual: Rp {{ number_format($auction->winning_bid, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $auction->auction_date?->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $auction->auction_date?->format('H:i') }} WIB</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php $colors = $auction->status_color; @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $colors['bg'] }} {{ $colors['text'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $colors['dot'] }} mr-1.5"></span>
                            {{ $auction->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('auctions.show', $auction->slug) }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Lihat di Website">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            @if(auth()->user()->hasPermission('auctions.edit'))
                            <a href="{{ route('admin.auctions.edit', $auction) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('auctions.delete'))
                            <button type="button" onclick="confirmDelete('{{ $auction->id }}', '{{ addslashes($auction->title) }}')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada data lelang</p>
                        <p class="text-sm text-slate-400 mt-1">Klik tombol "Tambah Lelang" untuk menambahkan</p>
                    </td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    {{-- Pagination --}}
    @if($auctions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $auctions->links() }}
        </div>
    @endif
</x-admin.card>

{{-- Delete Confirmation Modal --}}
<x-admin.delete-modal
    id="deleteModal"
    title="Hapus Lelang"
    message="Apakah Anda yakin ingin menghapus lelang ini? Semua gambar dan dokumen terkait juga akan dihapus."
/>

@push('scripts')
<script>
    function confirmDelete(id, title) {
        const modal = document.getElementById('deleteModal');
        const form = modal.querySelector('form');
        const messageEl = modal.querySelector('[data-message]');

        // Build the correct delete URL
        form.action = "{{ url('admin/auctions') }}/" + id;

        if (messageEl) {
            messageEl.textContent = `Apakah Anda yakin ingin menghapus lelang "${title}"? Semua gambar dan dokumen terkait juga akan dihapus.`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
@endpush
@endsection
