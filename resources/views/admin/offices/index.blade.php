@extends('layouts.admin')

@section('title', 'Kelola Kantor')

@section('content')
<x-admin.page-header title="Kelola Kantor" subtitle="Kelola informasi kantor dan cabang">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.offices.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Kantor
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    <div class="p-4 border-b border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kantor..."
                   class="w-full sm:flex-1 sm:min-w-[200px] rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <div class="flex gap-3">
                <select name="type" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Tipe</option>
                    <option value="pusat" {{ request('type') == 'pusat' ? 'selected' : '' }}>Kantor Pusat</option>
                    <option value="cabang" {{ request('type') == 'cabang' ? 'selected' : '' }}>Kantor Cabang</option>
                    <option value="kas" {{ request('type') == 'kas' ? 'selected' : '' }}>Kantor Kas</option>
                    <option value="kas_keliling" {{ request('type') == 'kas_keliling' ? 'selected' : '' }}>Kas Keliling</option>
                </select>
                <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                @if(request('search') || request('type'))
                    <a href="{{ route('admin.offices.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($offices as $office)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start gap-3 mb-3">
                    @if($office->photo)
                        <img src="{{ \App\Helpers\StorageHelper::url($office->photo) }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $office->name }}</p>
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $office->address }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <x-admin.badge variant="info">{{ $office->type_label }}</x-admin.badge>
                    @if($office->is_active)
                        <x-admin.badge variant="success">Aktif</x-admin.badge>
                    @else
                        <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                    @endif
                </div>
                @if($office->phone || $office->email)
                <div class="text-sm text-gray-500 mb-3">
                    @if($office->phone)<div>{{ $office->phone }}</div>@endif
                    @if($office->email)<div class="text-xs">{{ $office->email }}</div>@endif
                </div>
                @endif
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.offices.edit', $office) }}" class="flex-1 text-center py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kantor ini?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Belum ada data kantor.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Kantor', 'Tipe', 'Kontak', 'Status', 'Aksi']">
            @forelse($offices as $office)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($office->photo)
                                <img src="{{ \App\Helpers\StorageHelper::url($office->photo) }}" alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $office->name }}</p>
                                <p class="text-xs text-gray-500 truncate max-w-[200px]">{{ Str::limit($office->address, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <x-admin.badge variant="info">{{ $office->type_label }}</x-admin.badge>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        <div class="whitespace-nowrap">{{ $office->phone ?? '-' }}</div>
                        <div class="text-xs truncate max-w-[150px]">{{ $office->email ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($office->is_active)
                            <x-admin.badge variant="success">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.offices.edit', $office) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kantor ini?')">
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
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data kantor.</td></tr>
            @endforelse
        </x-admin.table>
    </div>

    @if($offices->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $offices->links() }}</div>
    @endif
</x-admin.card>
@endsection
