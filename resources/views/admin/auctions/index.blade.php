@extends('layouts.admin')

@section('title', 'Kelola Lelang Agunan')

@section('content')
<x-admin.page-header title="Kelola Lelang Agunan" subtitle="Daftar semua lelang agunan yang tersedia">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.auctions.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Lelang Agunan
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
    {{ session('error') }}
</div>
@endif

<x-admin.card :noPadding="true">
    <div class="p-4 border-b border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul, nomor lelang, alamat..."
                   class="w-full sm:flex-1 sm:min-w-[200px] rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <div class="flex flex-wrap gap-3">
                <select name="status" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Auction::$statusLabels as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <select name="asset_type" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\Auction::$assetTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('asset_type') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="city" value="{{ request('city') }}"
                       placeholder="Nama kota..."
                       class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                @if(request('search') || request('status') || request('asset_type') || request('city'))
                    <a href="{{ route('admin.auctions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($auctions as $auction)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start gap-3 mb-3">
                    @if($auction->images && is_array($auction->images) && count($auction->images) > 0)
                        <img src="{{ \App\Helpers\StorageHelper::url($auction->images[0]) }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 line-clamp-2">{{ $auction->title }}</p>
                        <p class="text-xs text-gray-500">{{ $auction->auction_number }}</p>
                    </div>
                </div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <x-admin.badge>{{ \App\Models\Auction::$assetTypes[$auction->asset_type] ?? $auction->asset_type }}</x-admin.badge>
                        <x-admin.badge variant="info">{{ $auction->status_label }}</x-admin.badge>
                        <span class="text-xs text-gray-500">{{ $auction->auction_date?->format('d M Y') ?? $auction->created_at->format('d M Y') }}</span>
                    </div>
                <div class="text-sm text-gray-600 mb-3">
                    <p class="font-medium">Rp {{ number_format($auction->limit_price, 0, ',', '.') }}</p>
                    <p class="text-xs">{{ $auction->city }}</p>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.auctions.edit', $auction) }}" class="flex-1 text-center py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.auctions.destroy', $auction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lelang ini?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Belum ada lelang agunan.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Lelang', 'Jenis', 'Status', 'Harga Limit', 'Tanggal', 'Aksi']">
            @forelse($auctions as $auction)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($auction->images && is_array($auction->images) && count($auction->images) > 0)
                                <img src="{{ \App\Helpers\StorageHelper::url($auction->images[0]) }}" alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate max-w-[250px]">{{ Str::limit($auction->title, 50) }}</p>
                                <p class="text-xs text-gray-500">{{ $auction->auction_number }}</p>
                                <p class="text-xs text-gray-500">{{ $auction->city }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <x-admin.badge>{{ \App\Models\Auction::$assetTypes[$auction->asset_type] ?? $auction->asset_type }}</x-admin.badge>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <x-admin.badge variant="info">{{ $auction->status_label }}</x-admin.badge>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                        {{ $auction->formatted_limit_price }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        @if($auction->auction_date)
                            {{ $auction->auction_date->format('d M Y') }}
                        @else
                            Belum ditentukan
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.auctions.edit', $auction) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.auctions.destroy', $auction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lelang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada lelang agunan.</td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    @if($auctions->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $auctions->links() }}
        </div>
    @endif
</x-admin.card>
@endsection
