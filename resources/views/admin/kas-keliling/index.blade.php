@extends('layouts.admin')

@section('title', 'Manajemen Kas Keliling')

@section('content')
<x-admin.page-header title="Kas Keliling" subtitle="Kelola jadwal kas keliling">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.kas-keliling.create') }}" icon="plus">
            Tambah Jadwal
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl">
    {{ session('success') }}
</div>
@endif

<x-admin.card>
    <!-- Filters -->
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari lokasi, PIC, fasilitas..." 
                   class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
        </div>
        <div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   placeholder="Dari Tanggal"
                   class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
        </div>
        <div>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   placeholder="Sampai Tanggal"
                   class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.kas-keliling.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                Reset
            </a>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $schedule->schedule_date->format('d M Y') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $schedule->day_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $schedule->time_range }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $schedule->location }}</div>
                        @if($schedule->facility)
                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($schedule->facility, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($schedule->pic_name)
                        <div class="text-sm text-gray-900">{{ $schedule->pic_name }}</div>
                        <div class="text-xs text-gray-500">{{ $schedule->pic_phone }}</div>
                        @else
                        <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($schedule->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Tidak Aktif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.kas-keliling.edit', $schedule) }}" 
                           class="text-emerald-600 hover:text-emerald-900">Edit</a>
                        <form action="{{ route('admin.kas-keliling.destroy', $schedule) }}" 
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        Belum ada jadwal kas keliling
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($schedules->hasPages())
    <div class="mt-6">
        {{ $schedules->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
