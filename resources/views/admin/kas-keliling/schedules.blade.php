@extends('layouts.admin')

@section('title', 'Jadwal Kas Keliling - ' . $kasKeliling->area_name)

@section('content')
<x-admin.page-header title="Jadwal Kas Keliling" :subtitle="$kasKeliling->area_name">
    <x-slot:actions>
        <button @click="$dispatch('open-modal', 'add-schedule')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal
        </button>
        <x-admin.button href="{{ route('admin.kas-keliling.index') }}" variant="secondary">
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
    {{ session('success') }}
</div>
@endif

<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $schedule->schedule_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $schedule->day_name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $schedule->start_time }} - {{ $schedule->end_time }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $schedule->location }}
                    </td>
                    <td class="px-6 py-4">
                        @if($schedule->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Tidak Aktif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                        <button @click="editSchedule({{ $schedule->id }})" class="text-blue-600 hover:text-blue-900">Edit</button>
                        <form action="{{ route('admin.kas-keliling.schedules.destroy', [$kasKeliling, $schedule]) }}" 
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
                        Belum ada jadwal
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

<!-- Add Schedule Modal -->
<div x-data="{ open: false }" 
     @open-modal.window="open = ($event.detail === 'add-schedule')"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        <div x-show="open" class="relative bg-white rounded-2xl max-w-2xl w-full p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Tambah Jadwal</h3>
            
            <form action="{{ route('admin.kas-keliling.schedules.store', $kasKeliling) }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-admin.input type="date" name="schedule_date" label="Tanggal" required />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Hari</label>
                        <select name="day_name" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <x-admin.input type="time" name="start_time" label="Jam Mulai" required />
                    <x-admin.input type="time" name="end_time" label="Jam Selesai" required />
                </div>
                
                <x-admin.input name="location" label="Lokasi" required placeholder="Contoh: Pasar Pagi" />
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Rute Perjalanan (Opsional)</label>
                    <div x-data="{ routes: [''] }">
                        <template x-for="(route, index) in routes" :key="index">
                            <div class="flex gap-2 mb-2">
                                <input type="text" :name="'route['+index+']'" x-model="routes[index]"
                                       placeholder="Contoh: Jl. Pasar Pagi"
                                       class="flex-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <button type="button" @click="routes.splice(index, 1)" x-show="routes.length > 1"
                                        class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="routes.push('')" class="text-sm text-blue-600 hover:text-green-700 font-medium">
                            + Tambah Rute
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Layanan yang Ditawarkan (Opsional)</label>
                    <div x-data="{ services: [''] }">
                        <template x-for="(service, index) in services" :key="index">
                            <div class="flex gap-2 mb-2">
                                <input type="text" :name="'services_offered['+index+']'" x-model="services[index]"
                                       placeholder="Contoh: Setoran Tabungan"
                                       class="flex-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <button type="button" @click="services.splice(index, 1)" x-show="services.length > 1"
                                        class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="services.push('')" class="text-sm text-blue-600 hover:text-green-700 font-medium">
                            + Tambah Layanan
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active_add" value="1" checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_active_add" class="ml-2 text-sm text-gray-700">Aktif</label>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <x-admin.button type="submit">Simpan</x-admin.button>
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
