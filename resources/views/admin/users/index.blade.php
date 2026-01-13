@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
<x-admin.page-header title="Kelola Pengguna" subtitle="Kelola akun pengguna admin">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.users.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Tambah Pengguna
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    <div class="p-4 border-b border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..."
                   class="w-full sm:flex-1 sm:min-w-[200px] rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <div class="flex gap-3">
                <select name="role" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Role</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="editor" {{ request('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                </select>
                <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($users as $user)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-emerald-600 font-semibold text-lg">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    @switch($user->role)
                        @case('super_admin')
                            <x-admin.badge variant="danger">Super Admin</x-admin.badge>
                            @break
                        @case('admin')
                            <x-admin.badge variant="primary">Admin</x-admin.badge>
                            @break
                        @case('editor')
                            <x-admin.badge variant="info">Editor</x-admin.badge>
                            @break
                    @endswitch
                    @if($user->roleModel)
                        <x-admin.badge variant="secondary">{{ $user->roleModel->display_name }}</x-admin.badge>
                    @endif
                    @if($user->is_active)
                        <x-admin.badge variant="success">Aktif</x-admin.badge>
                    @else
                        <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                    @endif
                    <span class="text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 text-center py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        Edit
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')" class="flex-1">
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
            <div class="text-center py-8 text-gray-500">Belum ada pengguna.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Pengguna', 'Role', 'Status', 'Dibuat', 'Aksi']">
            @forelse($users as $user)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-emerald-600 font-semibold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @switch($user->role)
                                @case('super_admin')
                                    <x-admin.badge variant="danger">Super Admin</x-admin.badge>
                                    @break
                                @case('admin')
                                    <x-admin.badge variant="primary">Admin</x-admin.badge>
                                    @break
                                @case('editor')
                                    <x-admin.badge variant="info">Editor</x-admin.badge>
                                    @break
                            @endswitch
                            @if($user->roleModel && $user->roleModel->name !== $user->role)
                                <x-admin.badge variant="secondary">{{ $user->roleModel->display_name }}</x-admin.badge>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($user->is_active)
                            <x-admin.badge variant="success">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
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
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada pengguna.</td></tr>
            @endforelse
        </x-admin.table>
    </div>

    @if($users->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</x-admin.card>
@endsection
