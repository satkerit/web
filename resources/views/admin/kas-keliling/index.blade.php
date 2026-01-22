@extends('layouts.admin')

@section('title', 'Manajemen Kas Keliling')

@section('content')
<x-admin.page-header title="Kas Keliling" subtitle="Kelola area dan jadwal kas keliling">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.kas-keliling.create') }}">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kas Keliling
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl">
    {{ session('success') }}
</div>
@endif

<x-admin.card>
    <!-- Search & Filter -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <form method="GET" class="flex-1 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari area atau contact person..." 
                   class="flex-1 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
            <select name="status" class="rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Area</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact Person</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($kasKelilings as $kasKeliling)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $kasKeliling->area_name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $kasKeliling->contact_person ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $kasKeliling->contact_phone ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $kasKeliling->schedules->count() }} Jadwal
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($kasKeliling->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Tidak Aktif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.kas-keliling.schedules', $kasKeliling) }}" 
                           class="text-blue-600 hover:text-blue-900">Jadwal</a>
                        <a href="{{ route('admin.kas-keliling.edit', $kasKeliling) }}" 
                           class="text-emerald-600 hover:text-emerald-900">Edit</a>
                        <form action="{{ route('admin.kas-keliling.destroy', $kasKeliling) }}" 
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        Belum ada data kas keliling
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($kasKelilings->hasPages())
    <div class="mt-6">
        {{ $kasKelilings->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
