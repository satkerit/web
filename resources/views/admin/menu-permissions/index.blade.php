@extends('layouts.admin')

@section('title', 'Hak Akses Menu')
@section('page-title', 'Hak Akses Menu')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Konfigurasi Hak Akses Menu</h2>
        <p class="text-sm text-gray-500 mt-1">Atur menu yang dapat diakses oleh setiap role</p>
    </div>

    <form action="{{ route('admin.menu-permissions.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Section</th>
                        @foreach($roles as $role => $roleName)
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $roleName }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $currentSection = null; @endphp
                    @foreach($menus as $menu)
                        @if($menu->section !== $currentSection)
                            @php $currentSection = $menu->section; @endphp
                            @if($currentSection)
                                <tr class="bg-gray-50">
                                    <td colspan="{{ 2 + count($roles) }}" class="px-6 py-2">
                                        <span class="text-xs font-bold text-gray-500 uppercase">{{ $currentSection }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endif
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">{{ $menu->name }}</span>
                                <span class="text-xs text-gray-400 block">{{ $menu->route }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">{{ $menu->section ?? '-' }}</span>
                            </td>
                            @foreach($roles as $role => $roleName)
                                @php
                                    $permission = $menu->permissions->first(function($p) use ($role) {
                                        return $p->role && $p->role->name === $role;
                                    });
                                    $canAccess = $permission ? $permission->can_access : false;
                                    $isDisabled = ($menu->key === 'menu-permissions' || $menu->key === 'users') && $role !== 'super_admin';
                                @endphp
                                <td class="px-6 py-4 text-center">
                                    <input
                                        type="checkbox"
                                        name="permissions[{{ $menu->id }}][{{ $role }}]"
                                        value="1"
                                        {{ $canAccess ? 'checked' : '' }}
                                        {{ $isDisabled ? 'disabled' : '' }}
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    >
                                    @if($isDisabled && $canAccess)
                                        <input type="hidden" name="permissions[{{ $menu->id }}][{{ $role }}]" value="1">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    <span class="text-amber-600">⚠</span> Menu "Hak Akses Menu" dan "Pengguna" hanya dapat diakses oleh Super Admin
                </p>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
