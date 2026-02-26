@extends('layouts.admin')

@section('title', 'Detail Role: ' . $role->display_name)

@section('content')
<x-admin.page-header :title="'Detail Role: ' . $role->display_name">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.roles.index') }}" variant="secondary">Kembali</x-admin.button>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->roleModel?->hasPermission('roles.edit'))
        <x-admin.button href="{{ route('admin.roles.edit', $role) }}">Edit Role</x-admin.button>
        @endif
    </x-slot:actions>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Role Info --}}
    <div class="lg:col-span-1 space-y-6">
        <x-admin.card title="Informasi Role">
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Nama Role</p>
                    <p class="font-mono text-sm text-gray-900">{{ $role->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Nama Tampilan</p>
                    <p class="font-medium text-gray-900">{{ $role->display_name }}</p>
                </div>
                @if($role->description)
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Deskripsi</p>
                    <p class="text-sm text-gray-700">{{ $role->description }}</p>
                </div>
                @endif
                <div class="flex items-center gap-2">
                    @if($role->is_active)
                        <x-admin.badge variant="success">Aktif</x-admin.badge>
                    @else
                        <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                    @endif
                    @if($role->is_system)
                        <x-admin.badge variant="warning">Role Sistem</x-admin.badge>
                    @endif
                </div>
            </div>
        </x-admin.card>

        <x-admin.card title="Statistik">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-xl">
                    <p class="text-2xl font-bold text-blue-600">{{ $role->users->count() }}</p>
                    <p class="text-xs text-gray-600">Pengguna</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <p class="text-2xl font-bold text-blue-600">{{ $role->permissions->count() }}</p>
                    <p class="text-xs text-gray-600">Permission</p>
                </div>
            </div>
        </x-admin.card>

        @if($role->users->count() > 0)
        <x-admin.card title="Pengguna dengan Role Ini">
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($role->users->take(10) as $user)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 font-semibold text-xs">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                @endforeach
                @if($role->users->count() > 10)
                <p class="text-xs text-gray-500 text-center pt-2">dan {{ $role->users->count() - 10 }} pengguna lainnya...</p>
                @endif
            </div>
        </x-admin.card>
        @endif
    </div>

    {{-- Permissions --}}
    <div class="lg:col-span-2">
        <x-admin.card title="Hak Akses (Permissions)" subtitle="Daftar permission yang dimiliki role ini">
            @php
                $groupedPermissions = $role->permissions->groupBy('group');
            @endphp

            @if($groupedPermissions->count() > 0)
            <div class="space-y-4">
                @foreach($groupedPermissions as $group => $permissions)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between">
                        <span class="font-medium text-gray-900">{{ $permissionGroups[$group] ?? ucfirst($group) }}</span>
                        <span class="text-xs text-gray-500">{{ $permissions->count() }} permission</span>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($permissions as $permission)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $permission->display_name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <p>Role ini belum memiliki permission.</p>
            </div>
            @endif
        </x-admin.card>
    </div>
</div>
@endsection
