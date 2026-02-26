@extends('layouts.admin')

@section('title', 'Backup Database')

@section('content')
<div class="space-y-6" x-data="backupManager()">
    {{-- Error Display --}}
    @if(isset($error))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="font-medium text-red-800">Terjadi Kesalahan</h3>
                <p class="text-sm text-red-600 mt-1">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Backup Database</h1>
            <p class="text-slate-600 mt-1">Kelola backup database untuk keamanan data</p>
        </div>
        <button @click="showModal = true"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Backup Baru
        </button>
    </div>

    {{-- Database Info Cards --}}
    @if(!isset($error))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Database Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Database</h3>
                    <p class="text-sm text-slate-600">{{ $databaseInfo['name'] ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Tabel:</span>
                    <span class="font-medium">{{ $databaseInfo['table_count'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Host:</span>
                    <span class="font-medium">{{ ($databaseInfo['host'] ?? 'N/A') }}:{{ ($databaseInfo['port'] ?? 'N/A') }}</span>
                </div>
            </div>
        </div>

        {{-- Storage Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Storage Backup</h3>
                    <p class="text-sm text-slate-600">{{ $storageInfo['total_backups'] ?? 0 }} file backup</p>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Total Backup:</span>
                    <span class="font-medium">{{ $storageInfo['total_backups'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Ukuran Total:</span>
                    <span class="font-medium">{{ $storageInfo['total_size_formatted'] ?? '0 B' }}</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Quick Backup</h3>
                    <p class="text-sm text-slate-600">Buat backup cepat</p>
                </div>
            </div>
            <div class="mt-4">
                <button @click="createQuickBackup()" :disabled="isCreating"
                    class="w-full px-4 py-2 bg-amber-600 hover:bg-amber-700 disabled:bg-amber-400 text-white rounded-lg transition-colors">
                    <span x-show="!isCreating">Quick Backup</span>
                    <span x-show="isCreating" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Membuat...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Backup List --}}
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Daftar Backup</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Ukuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($backups as $backup)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 text-sm">{{ $backup['filename'] }}</p>
                                        @if($backup['compressed'])
                                            <span class="text-xs text-blue-600">Compressed</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeLabels = [
                                        'full' => ['label' => 'Full Backup', 'class' => 'bg-blue-100 text-blue-800'],
                                        'structure_only' => ['label' => 'Structure Only', 'class' => 'bg-blue-100 text-blue-800'],
                                        'data_only' => ['label' => 'Data Only', 'class' => 'bg-purple-100 text-purple-800'],
                                        'unknown' => ['label' => 'Unknown', 'class' => 'bg-slate-100 text-slate-800'],
                                    ];
                                    $type = $typeLabels[$backup['type']] ?? $typeLabels['unknown'];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $type['class'] }}">
                                    {{ $type['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900">{{ $backup['size_formatted'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-900">
                                {{ $backup['created_at']->format('d/m/Y H:i') }}
                                <br>
                                <span class="text-xs text-slate-500">{{ $backup['created_at']->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.database-backup.download', $backup['filename']) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                    <button @click="confirmDelete('{{ $backup['filename'] }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-slate-900">Belum ada backup</h3>
                                        <p class="text-sm text-slate-500 mt-1">Buat backup pertama dengan mengklik tombol "Buat Backup Baru"</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Create Backup Modal --}}
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showModal = false"></div>

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Buat Backup Database</h3>
                        <p class="text-sm text-slate-600">Pilih jenis backup yang ingin dibuat</p>
                    </div>
                </div>

                <form @submit.prevent="createBackup()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Backup</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" x-model="backupType" value="full" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Full Backup (Struktur + Data)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="backupType" value="structure_only" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Struktur Saja (Tanpa Data)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="backupType" value="data_only" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Data Saja (Tanpa Struktur)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="compression" class="text-blue-600 focus:ring-blue-500 rounded">
                                <span class="ml-2 text-sm text-slate-700">Kompres file backup (gzip)</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi (Opsional)</label>
                            <input type="text" x-model="description"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: Backup sebelum update sistem">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-4 py-2 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit" :disabled="isCreating"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg transition-colors">
                            <span x-show="!isCreating">Buat Backup</span>
                            <span x-show="isCreating" class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Membuat...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('backupManager', () => ({
        showModal: false,
        isCreating: false,
        backupType: 'full',
        compression: true,
        description: '',

        async createBackup() {
            this.isCreating = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const response = await fetch('{{ route("admin.database-backup.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        backup_type: this.backupType,
                        compression: this.compression,
                        description: this.description
                    })
                });

                const data = await response.json();

                if (data.success) {
                    await window.Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#10b981'
                    });
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Gagal membuat backup');
                }
            } catch (error) {
                console.error('Error:', error);
                await window.Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat membuat backup.',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                this.isCreating = false;
                this.showModal = false;
            }
        },

        async createQuickBackup() {
            const result = await window.Swal.fire({
                title: 'Buat Quick Backup?',
                text: 'Backup full database dengan kompresi akan dibuat.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Buat Backup',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            this.isCreating = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const response = await fetch('{{ route("admin.database-backup.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        backup_type: 'full',
                        compression: true,
                        description: 'Quick backup'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    await window.Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#10b981'
                    });
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Gagal membuat backup');
                }
            } catch (error) {
                console.error('Error:', error);
                await window.Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat membuat backup.',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                this.isCreating = false;
            }
        },

        async confirmDelete(filename) {
            const result = await window.Swal.fire({
                title: 'Hapus Backup?',
                text: `File "${filename}" akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                await this.deleteBackup(filename);
            }
        },

        async deleteBackup(filename) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const deleteUrl = '{{ route("admin.database-backup.delete", ":filename") }}'.replace(':filename', filename);

                const response = await fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    await window.Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#10b981'
                    });
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Gagal menghapus backup');
                }
            } catch (error) {
                console.error('Error:', error);
                await window.Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat menghapus backup.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    }));
});
</script>
@endsection
