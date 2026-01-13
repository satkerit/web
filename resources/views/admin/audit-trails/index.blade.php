@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
<x-admin.page-header title="Log Aktivitas" subtitle="Riwayat semua aktivitas di sistem">
    @if(auth()->user()->isSuperAdmin())
    <x-slot:actions>
        <button type="button" onclick="document.getElementById('clearModal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Bersihkan Log Lama
        </button>
    </x-slot:actions>
    @endif
</x-admin.page-header>

<x-admin.card :noPadding="true">
    <!-- Filters -->
    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi, user, IP..."
                       class="w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">

                <select name="action" class="rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $action)) }}
                    </option>
                    @endforeach
                </select>

                <select name="user_id" class="rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>

                <select name="model_type" class="rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
                    <option value="">Semua Model</option>
                    @foreach($modelTypes as $type)
                    <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-slate-600">Dari:</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="rounded-xl border-0 py-2 px-3 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-slate-600">Sampai:</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="rounded-xl border-0 py-2 px-3 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm">
                </div>
                <div class="flex gap-2">
                    <x-admin.button type="submit" variant="secondary">Filter</x-admin.button>
                    @if(request()->hasAny(['search', 'action', 'user_id', 'model_type', 'date_from', 'date_to']))
                    <a href="{{ route('admin.audit-trails.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Detail</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($audits as $audit)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-900">{{ $audit->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-slate-500">{{ $audit->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-sm">
                                {{ strtoupper(substr($audit->user_name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-slate-900">{{ $audit->user_name ?? 'System' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $audit->action_badge }}">
                            @if($audit->action === 'create')
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            @elseif($audit->action === 'update')
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            @elseif($audit->action === 'delete')
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            @elseif($audit->action === 'login')
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            @elseif($audit->action === 'logout')
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $audit->action)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-900 max-w-xs truncate" title="{{ $audit->description }}">
                            {{ $audit->description }}
                        </div>
                        @if($audit->model_type)
                        <div class="text-xs text-slate-500">{{ class_basename($audit->model_type) }} #{{ $audit->model_id }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-slate-500 font-mono">{{ $audit->ip_address }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.audit-trails.show', $audit) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Belum ada log aktivitas.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($audits->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $audits->links() }}
    </div>
    @endif
</x-admin.card>

<!-- Clear Modal -->
<div id="clearModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('clearModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.audit-trails.clear') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Bersihkan Log Lama</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Hapus log aktivitas yang lebih lama dari jumlah hari yang ditentukan. Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hapus log lebih dari:</label>
                                <select name="days" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                    <option value="30">30 hari</option>
                                    <option value="60">60 hari</option>
                                    <option value="90" selected>90 hari</option>
                                    <option value="180">180 hari</option>
                                    <option value="365">1 tahun</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('clearModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">
                        Hapus Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
