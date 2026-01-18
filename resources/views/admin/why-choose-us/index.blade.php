@extends('layouts.admin')

@section('title', 'Kelola Keunggulan (Why Choose Us)')

@section('content')
<x-admin.page-header title="Kelola Keunggulan" subtitle="Kelola poin-poin 'Mengapa Memilih Kami' di halaman utama">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.why-choose-us.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Item
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    <div class="divide-y divide-gray-100" id="items-container">
        @forelse($items as $item)
            <div class="p-4 hover:bg-gray-50" data-id="{{ $item->id }}">
                {{-- Content --}}
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        @if($item->icon)
                            <img src="{{ Storage::url($item->icon) }}" alt="" class="w-16 h-16 object-contain rounded-lg bg-gray-50 p-2">
                        @else
                            <div class="w-16 h-16 bg-{{ $item->color_theme ?? 'primary' }}-100 rounded-lg flex items-center justify-center">
                                <span class="text-xs text-{{ $item->color_theme ?? 'primary' }}-600 font-bold">No Icon</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900">{{ $item->title }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $item->description }}</p>
                         <div class="flex items-center gap-2 mt-1">
                            @if($item->is_active)
                                <x-admin.badge variant="success" size="sm">Aktif</x-admin.badge>
                            @else
                                <x-admin.badge variant="danger" size="sm">Nonaktif</x-admin.badge>
                            @endif
                            <x-admin.badge variant="info" size="sm">{{ ucfirst($item->color_theme) }}</x-admin.badge>
                            <span class="text-xs text-gray-400">Urutan: {{ $item->sort_order }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.why-choose-us.edit', $item) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form action="{{ route('admin.why-choose-us.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                Belum ada data. Klik tombol "Tambah Item" untuk menambahkan.
            </div>
        @endforelse
    </div>
</x-admin.card>
@endsection
