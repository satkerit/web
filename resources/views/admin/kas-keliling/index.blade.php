@extends('layouts.admin')

@section('title', 'Manajemen Kas Keliling')

@section('content')
<x-admin.page-header title="Kas Keliling" subtitle="Kelola jadwal kas keliling">
    <x-slot:actions>
        <div class="flex items-center gap-3">
            <button id="exportBtn" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </button>
            <x-admin.button href="{{ route('admin.kas-keliling.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
                Tambah Jadwal
            </x-admin.button>
        </div>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
    {{ session('error') }}
</div>
@endif

<x-admin.card :noPadding="true">
    <div class="p-4 border-b border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari lokasi, PIC, fasilitas..." 
                   class="w-full sm:flex-1 sm:min-w-[200px] rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <div class="flex flex-wrap gap-3">
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       placeholder="Dari Tanggal"
                       class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       placeholder="Sampai Tanggal"
                       class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <select name="status" class="flex-1 sm:flex-none rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                @if(request('search') || request('date_from') || request('date_to') || request('status'))
                    <a href="{{ route('admin.kas-keliling.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-white rounded-lg ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="hidden p-4 bg-blue-50 border-b border-blue-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span id="selectedCount" class="text-sm font-medium text-blue-900">0 item dipilih</span>
                <div class="flex items-center gap-2">
                    <button id="bulkActivateBtn" class="px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-100 rounded-lg hover:bg-emerald-200 transition-colors">
                        Aktifkan
                    </button>
                    <button id="bulkDeactivateBtn" class="px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-100 rounded-lg hover:bg-amber-200 transition-colors">
                        Nonaktifkan
                    </button>
                    <button id="bulkDeleteBtn" class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
            <button id="clearSelectionBtn" class="text-sm text-blue-600 hover:text-blue-800">
                Batal Pilih
            </button>
        </div>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-4">
        @forelse($schedules as $schedule)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900">{{ $schedule->location }}</p>
                        <p class="text-xs text-gray-500">{{ $schedule->schedule_date->format('d M Y') }} - {{ $schedule->day_name }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <x-admin.badge variant="info">{{ $schedule->time_range }}</x-admin.badge>
                    @if($schedule->is_active)
                        <x-admin.badge variant="success">Aktif</x-admin.badge>
                    @else
                        <x-admin.badge variant="secondary">Tidak Aktif</x-admin.badge>
                    @endif
                </div>
                @if($schedule->pic_name)
                    <div class="text-sm text-gray-600 mb-3">
                        <p class="font-medium">PIC: {{ $schedule->pic_name }}</p>
                        @if($schedule->pic_phone)
                            <p class="text-xs">{{ $schedule->pic_phone }}</p>
                        @endif
                    </div>
                @endif
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.kas-keliling.edit', $schedule) }}" class="flex-1 text-center py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.kas-keliling.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Belum ada jadwal kas keliling.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['', 'Tanggal', 'Lokasi', 'Waktu', 'PIC', 'Status', 'Aksi']">
            @forelse($schedules as $schedule)
                <tr>
                    <td class="px-4 py-3">
                        <input type="checkbox" class="schedule-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" 
                               value="{{ $schedule->id }}" data-id="{{ $schedule->id }}">
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $schedule->schedule_date->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $schedule->day_name }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $schedule->location }}</p>
                            @if($schedule->facility)
                                <p class="text-xs text-gray-500">{{ Str::limit($schedule->facility, 50) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <x-admin.badge variant="info">{{ $schedule->time_range }}</x-admin.badge>
                    </td>
                    <td class="px-4 py-3">
                        @if($schedule->pic_name)
                            <div>
                                <p class="font-medium text-gray-900">{{ $schedule->pic_name }}</p>
                                @if($schedule->pic_phone)
                                    <p class="text-xs text-gray-500">{{ $schedule->pic_phone }}</p>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($schedule->is_active)
                            <x-admin.badge variant="success">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge variant="secondary">Tidak Aktif</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.kas-keliling.edit', $schedule) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.kas-keliling.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
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
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada jadwal kas keliling.</td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    @if($schedules->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $schedules->links() }}
        </div>
    @endif
</x-admin.card>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.schedule-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const bulkActivateBtn = document.getElementById('bulkActivateBtn');
    const bulkDeactivateBtn = document.getElementById('bulkDeactivateBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const exportBtn = document.getElementById('exportBtn');

    function updateBulkActions() {
        const selected = document.querySelectorAll('.schedule-checkbox:checked');
        const count = selected.length;
        
        if (count > 0) {
            bulkActionsBar.classList.remove('hidden');
            selectedCount.textContent = `${count} item dipilih`;
        } else {
            bulkActionsBar.classList.add('hidden');
        }
    }

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.schedule-checkbox:checked'))
                   .map(cb => cb.value);
    }

    // Checkbox change handler
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Clear selection
    clearSelectionBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        updateBulkActions();
    });

    // Bulk activate
    bulkActivateBtn.addEventListener('click', function() {
        const ids = getSelectedIds();
        if (ids.length === 0) return;

        if (confirm(`Yakin ingin mengaktifkan ${ids.length} jadwal?`)) {
            bulkUpdateStatus(ids, true);
        }
    });

    // Bulk deactivate
    bulkDeactivateBtn.addEventListener('click', function() {
        const ids = getSelectedIds();
        if (ids.length === 0) return;

        if (confirm(`Yakin ingin menonaktifkan ${ids.length} jadwal?`)) {
            bulkUpdateStatus(ids, false);
        }
    });

    // Bulk delete
    bulkDeleteBtn.addEventListener('click', function() {
        const ids = getSelectedIds();
        if (ids.length === 0) return;

        if (confirm(`Yakin ingin menghapus ${ids.length} jadwal? Tindakan ini tidak dapat dibatalkan.`)) {
            bulkDelete(ids);
        }
    });

    // Export button
    exportBtn.addEventListener('click', function() {
        const params = new URLSearchParams(window.location.search);
        const exportUrl = '{{ route("admin.kas-keliling.export") }}?' + params.toString();
        window.location.href = exportUrl;
    });

    function bulkUpdateStatus(ids, status) {
        fetch('{{ route("admin.kas-keliling.bulk-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ids: ids,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }

    function bulkDelete(ids) {
        fetch('{{ route("admin.kas-keliling.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ids: ids
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }
});
</script>
@endpush
@endsection
