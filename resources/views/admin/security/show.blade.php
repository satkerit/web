@extends('admin.layouts.app')

@section('title', 'Detail Ancaman')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.security-monitor.index') }}"
           class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Ancaman #{{ $securityLog->id }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $securityLog->created_at->format('d F Y, H:i:s') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Threat Type Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Tipe Ancaman</span>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $securityLog->getThreatInfo()['label'] }}
                        </h2>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full {{ $securityLog->getThreatBadgeClass() }}">
                        {{ \App\Models\SecurityLog::THREAT_LEVELS[$securityLog->threat_level]['label'] ?? $securityLog->threat_level }}
                    </span>
                </div>

                @if($securityLog->was_blocked)
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center gap-2 text-red-700 dark:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span class="font-medium">IP ini telah diblokir</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Request Details --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Request</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Method & URL</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono bg-gray-100 dark:bg-gray-700 p-3 rounded-lg overflow-x-auto">
                            <span class="inline-block px-2 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded mr-2">{{ $securityLog->request_method }}</span>
                            {{ $securityLog->request_url }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User Agent</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono bg-gray-100 dark:bg-gray-700 p-3 rounded-lg overflow-x-auto">
                            {{ $securityLog->user_agent ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Matched Pattern --}}
            @if($securityLog->matched_pattern)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pola yang Terdeteksi</h3>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <code class="text-sm text-yellow-800 dark:text-yellow-200 break-all">{{ $securityLog->matched_pattern }}</code>
                </div>
            </div>
            @endif

            {{-- Raw Input --}}
            @if($securityLog->raw_input)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Input yang Mencurigakan</h3>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-sm text-red-800 dark:text-red-200 whitespace-pre-wrap break-all">{{ $securityLog->raw_input }}</pre>
                </div>
            </div>
            @endif

            {{-- Payload --}}
            @if($securityLog->payload)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Payload</h3>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-sm text-gray-800 dark:text-gray-200">{{ json_encode($securityLog->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- IP Info --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi IP</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">IP Address</dt>
                        <dd class="mt-1 font-mono text-gray-900 dark:text-white">{{ $securityLog->ip_address }}</dd>
                    </div>
                    @if($securityLog->country_code)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Negara</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $securityLog->country_code }}</dd>
                    </div>
                    @endif
                    @if($securityLog->user)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $securityLog->user->name }}</dd>
                    </div>
                    @endif
                </dl>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    @if(\App\Models\BlockedIp::isBlocked($securityLog->ip_address))
                        <button onclick="unblockIp('{{ $securityLog->ip_address }}')"
                                class="w-full px-4 py-2 text-sm font-medium text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-300 dark:bg-green-900/30 dark:hover:bg-green-900/50 rounded-lg transition-colors">
                            Buka Blokir IP
                        </button>
                    @else
                        <button onclick="blockThisIp()"
                                class="w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 dark:text-red-300 dark:bg-red-900/30 dark:hover:bg-red-900/50 rounded-lg transition-colors">
                            Blokir IP Ini
                        </button>
                    @endif
                </div>
            </div>

            {{-- Related Threats --}}
            @if($relatedThreats->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Ancaman Lain dari IP Ini
                    <span class="text-sm font-normal text-gray-500">({{ \App\Models\SecurityLog::where('ip_address', $securityLog->ip_address)->count() }} total)</span>
                </h3>
                <div class="space-y-2">
                    @foreach($relatedThreats as $related)
                    <a href="{{ route('admin.security-monitor.show', $related) }}"
                       class="block p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-900 dark:text-white">{{ $related->getThreatInfo()['label'] }}</span>
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $related->getThreatBadgeClass() }}">
                                {{ \App\Models\SecurityLog::THREAT_LEVELS[$related->threat_level]['label'] ?? $related->threat_level }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $related->created_at->diffForHumans() }}
                        </p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $nonce }}">
async function blockThisIp() {
    const reason = prompt('Alasan pemblokiran (opsional):');

    try {
        const response = await fetch('{{ route("admin.security-monitor.block-ip") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                ip_address: '{{ $securityLog->ip_address }}',
                reason: reason || 'Blocked from threat detail page',
                duration: 24,
                permanent: false,
            }),
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert(result.message || 'Gagal memblokir IP');
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    }
}

async function unblockIp(ip) {
    if (!confirm(`Yakin ingin membuka blokir IP ${ip}?`)) return;

    try {
        const response = await fetch(`{{ url('admin/security-monitor/unblock') }}/${ip}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert(result.message || 'Gagal membuka blokir IP');
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    }
}
</script>
@endpush
@endsection
