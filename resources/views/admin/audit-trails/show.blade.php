@extends('layouts.admin')

@section('title', 'Detail Log Aktivitas')

@section('content')
<x-admin.page-header title="Detail Log Aktivitas" subtitle="Informasi lengkap aktivitas">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.audit-trails.index') }}" variant="secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Info -->
        <x-admin.card title="Informasi Aktivitas">
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Aksi</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $auditTrail->action_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $auditTrail->action)) }}
                    </span>
                </div>
                <div class="flex items-start justify-between py-3 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Deskripsi</span>
                    <span class="text-sm text-slate-900 text-right max-w-md">{{ $auditTrail->description }}</span>
                </div>
                @if($auditTrail->model_type)
                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Model</span>
                    <span class="text-sm text-slate-900 font-mono">{{ class_basename($auditTrail->model_type) }} #{{ $auditTrail->model_id }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Waktu</span>
                    <span class="text-sm text-slate-900">{{ $auditTrail->created_at->format('d F Y, H:i:s') }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-slate-500">Waktu Relatif</span>
                    <span class="text-sm text-slate-900">{{ $auditTrail->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </x-admin.card>

        <!-- Changes -->
        @if($auditTrail->old_values || $auditTrail->new_values)
        <x-admin.card title="Perubahan Data">
            <div class="space-y-4">
                @if($auditTrail->action === 'create' && $auditTrail->new_values)
                    <div class="bg-green-50 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Data Baru
                        </h4>
                        <div class="space-y-2">
                            @foreach($auditTrail->new_values as $key => $value)
                                @if(!in_array($key, ['password', 'remember_token', 'created_at', 'updated_at']))
                                <div class="flex items-start text-sm">
                                    <span class="font-medium text-green-700 w-40 flex-shrink-0">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    <span class="text-green-900 break-all">
                                        @if(is_array($value))
                                            <pre class="text-xs bg-green-100 rounded p-2 overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            {{ $value ?? '-' }}
                                        @endif
                                    </span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @elseif($auditTrail->action === 'delete' && $auditTrail->old_values)
                    <div class="bg-red-50 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-red-800 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Data yang Dihapus
                        </h4>
                        <div class="space-y-2">
                            @foreach($auditTrail->old_values as $key => $value)
                                @if(!in_array($key, ['password', 'remember_token', 'created_at', 'updated_at']))
                                <div class="flex items-start text-sm">
                                    <span class="font-medium text-red-700 w-40 flex-shrink-0">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    <span class="text-red-900 break-all">
                                        @if(is_array($value))
                                            <pre class="text-xs bg-red-100 rounded p-2 overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            {{ $value ?? '-' }}
                                        @endif
                                    </span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @elseif($auditTrail->action === 'update')
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Field</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nilai Lama</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nilai Baru</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($auditTrail->new_values ?? [] as $key => $newValue)
                                    @if(!in_array($key, ['password', 'remember_token', 'updated_at']))
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-700">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-red-600 bg-red-50">
                                            @php $oldValue = $auditTrail->old_values[$key] ?? null; @endphp
                                            @if(is_array($oldValue))
                                                <pre class="text-xs overflow-x-auto">{{ json_encode($oldValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            @else
                                                {{ $oldValue ?? '-' }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-green-600 bg-green-50">
                                            @if(is_array($newValue))
                                                <pre class="text-xs overflow-x-auto">{{ json_encode($newValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            @else
                                                {{ $newValue ?? '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </x-admin.card>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- User Info -->
        <x-admin.card title="Informasi User">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-lg">
                    {{ strtoupper(substr($auditTrail->user_name ?? 'S', 0, 1)) }}
                </div>
                <div class="ml-3">
                    <p class="font-semibold text-slate-900">{{ $auditTrail->user_name ?? 'System' }}</p>
                    @if($auditTrail->user)
                    <p class="text-sm text-slate-500">{{ $auditTrail->user->email }}</p>
                    @endif
                </div>
            </div>
            @if($auditTrail->user)
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Role</span>
                    <span class="font-medium text-slate-900">{{ $auditTrail->user->roleModel?->display_name ?? 'N/A' }}</span>
                </div>
            </div>
            @endif
        </x-admin.card>

        <!-- Request Info -->
        <x-admin.card title="Informasi Request">
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">IP Address</span>
                    <span class="font-mono text-slate-900">{{ $auditTrail->ip_address ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Method</span>
                    <span class="font-mono text-slate-900">{{ $auditTrail->method ?? '-' }}</span>
                </div>
                @if($auditTrail->url)
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-sm text-slate-500 block mb-1">URL</span>
                    <span class="text-xs font-mono text-slate-700 break-all">{{ $auditTrail->url }}</span>
                </div>
                @endif
                @if($auditTrail->user_agent)
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-sm text-slate-500 block mb-1">User Agent</span>
                    <span class="text-xs text-slate-700 break-all">{{ Str::limit($auditTrail->user_agent, 100) }}</span>
                </div>
                @endif
            </div>
        </x-admin.card>
    </div>
</div>
@endsection
