@extends('layouts.admin')

@section('title', 'Pengaduan Nasabah')

@section('content')
<x-admin.page-header title="Pengaduan Nasabah" subtitle="Kelola pengaduan dan keluhan nasabah">
    <x-slot:actions>
        <a href="{{ route('admin.customer-complaints.print', request()->query()) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Laporan
        </a>
    </x-slot:actions>
</x-admin.page-header>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                <p class="text-sm text-gray-500">Menunggu</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
                <p class="text-sm text-gray-500">Diproses</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['resolved'] }}</p>
                <p class="text-sm text-gray-500">Selesai</p>
            </div>
        </div>
    </div>
</div>

<x-admin.card :noPadding="true">
    <div class="p-4 border-b border-gray-100">
        <form method="GET" class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tiket/nama/subjek..."
                       class="w-full sm:flex-1 sm:min-w-[200px] rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <div class="flex flex-wrap gap-3">
                    <select name="status" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                    <select name="category" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <option value="service" {{ request('category') == 'service' ? 'selected' : '' }}>Pelayanan</option>
                    <option value="product" {{ request('category') == 'product' ? 'selected' : '' }}>Produk</option>
                    <option value="transaction" {{ request('category') == 'transaction' ? 'selected' : '' }}>Transaksi</option>
                    <option value="facility" {{ request('category') == 'facility' ? 'selected' : '' }}>Fasilitas</option>
                    <option value="staff" {{ request('category') == 'staff' ? 'selected' : '' }}>Petugas</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <select name="priority" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Prioritas</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                </select>
                <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                @if(request('search') || request('status') || request('category') || request('priority'))
                    <a href="{{ route('admin.customer-complaints.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
            {{-- Filter Tanggal --}}
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <span class="text-xs text-gray-500 font-medium">Periode:</span>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Dari tanggal">
                <span class="text-xs text-gray-400">s/d</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Sampai tanggal">
                @if(request('date_from') || request('date_to'))
                    <span class="text-xs text-emerald-600 font-medium">● Filter tanggal aktif</span>
                @endif
            </div>
        </form>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($complaints as $complaint)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="mb-3">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $complaint->ticket_number }}</p>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $complaint->subject }}</p>
                        </div>
                        @switch($complaint->status)
                            @case('pending')
                                <x-admin.badge variant="warning">Menunggu</x-admin.badge>
                                @break
                            @case('in_progress')
                                <x-admin.badge variant="info">Diproses</x-admin.badge>
                                @break
                            @case('resolved')
                                <x-admin.badge variant="success">Selesai</x-admin.badge>
                                @break
                            @case('closed')
                                <x-admin.badge>Ditutup</x-admin.badge>
                                @break
                        @endswitch
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-900">{{ $complaint->name }}</p>
                        <p class="text-xs text-gray-500">{{ $complaint->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <x-admin.badge>{{ $complaint->category_label }}</x-admin.badge>
                    @if($complaint->subcategory)
                        <x-admin.badge variant="info">{{ $complaint->subcategory_label }}</x-admin.badge>
                    @endif
                    @if($complaint->priority === 'high')
                        <x-admin.badge variant="danger">Prioritas Tinggi</x-admin.badge>
                    @endif
                    <span class="text-xs text-gray-500">{{ $complaint->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.customer-complaints.show', $complaint) }}" class="flex-1 text-center py-2 text-sm font-medium text-blue-600 hover:bg-green-50 rounded-lg transition-colors">
                        Lihat Detail
                    </a>
                    <form action="{{ route('admin.customer-complaints.destroy', $complaint) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengaduan ini?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Belum ada pengaduan nasabah.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Tiket', 'Nasabah', 'Kategori', 'Prioritas', 'Status', 'Tanggal', 'Aksi']">
            @forelse($complaints as $complaint)
                <tr>
                    <td class="px-4 py-3">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900">{{ $complaint->ticket_number }}</p>
                            <p class="text-xs text-gray-500 truncate max-w-[200px]">{{ Str::limit($complaint->subject, 40) }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="min-w-0">
                            <p class="text-gray-900 truncate">{{ $complaint->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $complaint->phone }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex flex-col gap-1">
                            <x-admin.badge>{{ $complaint->category_label }}</x-admin.badge>
                            @if($complaint->subcategory)
                                <x-admin.badge variant="info" class="text-[10px]">{{ $complaint->subcategory_label }}</x-admin.badge>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @switch($complaint->priority)
                            @case('high')
                                <x-admin.badge variant="danger">Tinggi</x-admin.badge>
                                @break
                            @case('medium')
                                <x-admin.badge variant="warning">Sedang</x-admin.badge>
                                @break
                            @case('low')
                                <x-admin.badge variant="info">Rendah</x-admin.badge>
                                @break
                        @endswitch
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @switch($complaint->status)
                            @case('pending')
                                <x-admin.badge variant="warning">Menunggu</x-admin.badge>
                                @break
                            @case('in_progress')
                                <x-admin.badge variant="info">Diproses</x-admin.badge>
                                @break
                            @case('resolved')
                                <x-admin.badge variant="success">Selesai</x-admin.badge>
                                @break
                            @case('closed')
                                <x-admin.badge>Ditutup</x-admin.badge>
                                @break
                        @endswitch
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $complaint->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.customer-complaints.show', $complaint) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-green-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.customer-complaints.destroy', $complaint) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengaduan ini?')">
                                @csrf @method('DELETE')
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
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada pengaduan nasabah.</td></tr>
            @endforelse
        </x-admin.table>
    </div>

    @if($complaints->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $complaints->links() }}</div>
    @endif
</x-admin.card>
@endsection
