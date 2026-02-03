@extends('layouts.admin')

@section('title', 'Kelola Hero Slides')

@section('content')
<x-admin.page-header title="Kelola Hero Slides" subtitle="Kelola slider banner di halaman utama">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.hero-slides.settings') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'>
            Pengaturan
        </x-admin.button>
        <x-admin.button href="{{ route('admin.hero-slides.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Slide
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    <div class="divide-y divide-gray-100" id="slides-container">
        @forelse($slides as $slide)
            <div class="p-4 hover:bg-gray-50" data-id="{{ $slide->id }}">
                {{-- Mobile Layout --}}
                <div class="block sm:hidden">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="cursor-move text-gray-400 hover:text-gray-600 flex-shrink-0 pt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                        </div>
                        @if($slide->image)
                            <img src="{{ \App\Helpers\StorageHelper::url($slide->image) }}" alt="" class="w-20 h-14 object-cover rounded-lg flex-shrink-0">
                        @else
                            <div class="w-20 h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $slide->title ?: 'Tanpa Judul' }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $slide->subtitle ?: 'Tanpa subtitle' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($slide->is_active)
                                <x-admin.badge variant="success" size="sm">Aktif</x-admin.badge>
                            @else
                                <x-admin.badge variant="danger" size="sm">Nonaktif</x-admin.badge>
                            @endif
                            <span class="text-xs text-gray-400">Urutan: {{ $slide->order_position }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slide ini?')">
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

                {{-- Desktop Layout --}}
                <div class="hidden sm:flex items-center gap-4">
                    <div class="cursor-move text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                    </div>
                    <div class="flex-shrink-0">
                        @if($slide->image)
                            <img src="{{ \App\Helpers\StorageHelper::url($slide->image) }}" alt="" class="w-32 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-32 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900">{{ $slide->title ?: 'Tanpa Judul' }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $slide->subtitle ?: 'Tanpa subtitle' }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @if($slide->is_active)
                                <x-admin.badge variant="success" size="sm">Aktif</x-admin.badge>
                            @else
                                <x-admin.badge variant="danger" size="sm">Nonaktif</x-admin.badge>
                            @endif
                            <span class="text-xs text-gray-400">Urutan: {{ $slide->order_position }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slide ini?')">
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
                Belum ada slide. Klik tombol "Tambah Slide" untuk menambahkan.
            </div>
        @endforelse
    </div>
</x-admin.card>
@endsection
