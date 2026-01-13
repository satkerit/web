@extends('layouts.admin')

@section('title', 'Simple Backup Database')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Simple Backup Database</h1>
            <p class="text-slate-600 mt-1">Test backup functionality</p>
        </div>
        <button onclick="createBackup()"
            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Backup Test
        </button>
    </div>

    {{-- Database Info --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Database Info</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-slate-600">Database:</span>
                <span class="font-medium ml-2">{{ $databaseInfo['name'] }}</span>
            </div>
            <div>
                <span class="text-slate-600">Host:</span>
                <span class="font-medium ml-2">{{ $databaseInfo['host'] }}:{{ $databaseInfo['port'] }}</span>
            </div>
            <div>
                <span class="text-slate-600">Tables:</span>
                <span class="font-medium ml-2">{{ $databaseInfo['table_count'] }}</span>
            </div>
            <div>
                <span class="text-slate-600">Total Backups:</span>
                <span class="font-medium ml-2">{{ $storageInfo['total_backups'] }}</span>
            </div>
        </div>
    </div>

    {{-- Backup List --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Backup Files</h2>
        </div>

        @if($backups->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($backups as $backup)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $backup['filename'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $backup['size_formatted'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $backup['created_at']->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-slate-600">Belum ada backup. Klik tombol "Buat Backup Test" untuk membuat backup pertama.</p>
            </div>
        @endif
    </div>
</div>

<script>
async function createBackup() {
    try {
        const response = await fetch('{{ route("admin.simple-backup.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('Backup berhasil dibuat: ' + data.filename);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}
</script>
@endsection
