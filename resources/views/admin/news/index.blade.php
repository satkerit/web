@extends('layouts.admin')

@section('title', 'Kelola Berita')

@section('content')
<x-admin.page-header title="Kelola Berita" subtitle="Kelola semua berita dan artikel">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.news.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Berita
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    <div class="p-4 border-b border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..."
                   class="w-full sm:flex-1 sm:min-w-[200px] rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <div class="flex flex-wrap gap-3">
                <select name="category" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Kategori</option>
                    <option value="Berita" {{ request('category') == 'Berita' ? 'selected' : '' }}>Berita</option>
                    <option value="Artikel" {{ request('category') == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                    <option value="Pengumuman" {{ request('category') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="Promo" {{ request('category') == 'Promo' ? 'selected' : '' }}>Promo</option>
                </select>
                <select name="status" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasi</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                @if(request('search') || request('category') || request('status'))
                    <a href="{{ route('admin.news.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($news as $item)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start gap-3 mb-3">
                    @if($item->featured_image)
                        <img src="{{ \App\Helpers\StorageHelper::url($item->featured_image) }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 line-clamp-2">{{ $item->title }}</p>
                        <p class="text-xs text-gray-500">{{ $item->author ?? 'Admin' }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <x-admin.badge>{{ ucfirst($item->category) }}</x-admin.badge>
                    @if($item->is_published)
                        <x-admin.badge variant="success">Dipublikasi</x-admin.badge>
                    @else
                        <x-admin.badge variant="warning">Draft</x-admin.badge>
                    @endif
                    <span class="text-xs text-gray-500">{{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.news.edit', $item) }}" class="flex-1 text-center py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Belum ada berita.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Berita', 'Kategori', 'Status', 'Tanggal', 'Aksi']">
            @forelse($news as $item)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($item->featured_image)
                                <img src="{{ \App\Helpers\StorageHelper::url($item->featured_image) }}" alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate max-w-[250px]">{{ Str::limit($item->title, 50) }}</p>
                                <p class="text-xs text-gray-500">{{ $item->author ?? 'Admin' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <x-admin.badge>{{ ucfirst($item->category) }}</x-admin.badge>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($item->is_published)
                            <x-admin.badge variant="success">Dipublikasi</x-admin.badge>
                        @else
                            <x-admin.badge variant="warning">Draft</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        {{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.news.edit', $item) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada berita.</td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    @if($news->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $news->links() }}
        </div>
    @endif
</x-admin.card>
@endsection
