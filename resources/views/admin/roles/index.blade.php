@extends('layouts.admin')

@section('title', 'Manajemen Role')

@section('content')
<x-admin.page-header title="Manajemen Role" subtitle="Kelola role dan hak akses pengguna">
    <x-slot:actions>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->roleModel?->hasPermission('roles.create'))
        <x-admin.button href="{{ route('admin.roles.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Role
        </x-admin.button>
        @endif
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    <div class="p-4 border-b border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari role..."
                   class="w-full sm:flex-1 sm:min-w-[200px] rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            <div class="flex gap-3">
                <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                @if(request('search'))
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($roles as $role)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $role->display_name }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $role->name }}</p>
                    </div>
                    @if($role->is_system)
                        <x-admin.badge variant="warning">Sistem</x-admin.badge>
                    @endif
                </div>
                @if($role->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($role->description, 80) }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    @if($role->is_active)
                        <x-admin.badge variant="success">Aktif</x-admin.badge>
                    @else
                        <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                    @endif
                    <span class="text-xs text-gray-500">{{ $role->users_count }} pengguna</span>
                    <span class="text-xs text-gray-500">{{ $role->permissions_count }} permission</span>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->roleModel?->hasPermission('roles.edit'))
                    <a href="{{ route('admin.roles.edit', $role) }}" class="flex-1 text-center py-2 text-sm font-medium text-blue-600 hover:bg-green-50 rounded-lg transition-colors">
                        Edit
                    </a>
                    @endif
                    @if(!$role->is_system && (auth()->user()->isSuperAdmin() || auth()->user()->roleModel?->hasPermission('roles.delete')))
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus role ini?')" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                Hapus
                            </button>
                        </form>
                    @else
                        <div class="flex-1"></div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Belum ada role.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Role', 'Deskripsi', 'Pengguna', 'Permission', 'Status', 'Aksi']">
            @forelse($roles as $role)
                <tr>
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $role->display_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $role->name }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                        <span title="{{ $role->description }}">{{ Str::limit($role->description, 50) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                        {{ $role->users_count }} pengguna
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                        {{ $role->permissions_count }} permission
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            @if($role->is_active)
                                <x-admin.badge variant="success">Aktif</x-admin.badge>
                            @else
                                <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                            @endif
                            @if($role->is_system)
                                <x-admin.badge variant="warning">Sistem</x-admin.badge>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.roles.show', $role) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->roleModel?->hasPermission('roles.edit'))
                            <a href="{{ route('admin.roles.edit', $role) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-green-50 rounded-lg" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @endif
                            @if(!$role->is_system && (auth()->user()->isSuperAdmin() || auth()->user()->roleModel?->hasPermission('roles.delete')))
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus role ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada role.</td></tr>
            @endforelse
        </x-admin.table>
    </div>

    @if($roles->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $roles->links() }}</div>
    @endif
</x-admin.card>
@endsection
