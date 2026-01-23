@extends('layouts.admin')

@section('title', 'Pengaturan Keamanan')

@section('content')
<x-admin.page-header title="Pengaturan Keamanan" subtitle="Kelola pengaturan keamanan website">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.settings.blocked-ips') }}" variant="secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            Kelola IP Terblokir
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl">
    {{ session('success') }}
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total IP Terblokir</p>
                <p class="text-3xl font-bold text-gray-900">{{ $blockedIpsCount }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Blokir Permanen</p>
                <p class="text-3xl font-bold text-gray-900">{{ $permanentBlocksCount }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Blokir Sementara</p>
                <p class="text-3xl font-bold text-gray-900">{{ $temporaryBlocksCount }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.settings.security.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rate Limiting Settings -->
        <x-admin.card>
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Rate Limiting</h3>
                <p class="text-sm text-gray-600">Batasi jumlah request untuk mencegah abuse</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Web (requests/minute)
                    </label>
                    <input type="number" name="rate_limit_web" value="{{ old('rate_limit_web', $settings->rate_limit_web) }}" 
                           min="10" max="1000" required
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-1 text-xs text-gray-500">Jumlah maksimal request per menit untuk halaman publik</p>
                    @error('rate_limit_web')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Admin (requests/minute)
                    </label>
                    <input type="number" name="rate_limit_admin" value="{{ old('rate_limit_admin', $settings->rate_limit_admin) }}" 
                           min="10" max="500" required
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-1 text-xs text-gray-500">Jumlah maksimal request per menit untuk halaman admin</p>
                    @error('rate_limit_admin')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Login (attempts/minute)
                    </label>
                    <input type="number" name="rate_limit_login" value="{{ old('rate_limit_login', $settings->rate_limit_login) }}" 
                           min="1" max="20" required
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-1 text-xs text-gray-500">Jumlah maksimal percobaan login per menit</p>
                    @error('rate_limit_login')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Reset (attempts/minute)
                    </label>
                    <input type="number" name="rate_limit_password_reset" value="{{ old('rate_limit_password_reset', $settings->rate_limit_password_reset) }}" 
                           min="1" max="10" required
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-1 text-xs text-gray-500">Jumlah maksimal request reset password per menit</p>
                    @error('rate_limit_password_reset')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Download (requests/minute)
                    </label>
                    <input type="number" name="rate_limit_download" value="{{ old('rate_limit_download', $settings->rate_limit_download) }}" 
                           min="5" max="100" required
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-1 text-xs text-gray-500">Jumlah maksimal download per menit</p>
                    @error('rate_limit_download')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-admin.card>

        <!-- IP Blocking Settings -->
        <x-admin.card>
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">IP Blocking</h3>
                <p class="text-sm text-gray-600">Kelola pemblokiran IP otomatis</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Block Threshold (failed attempts)
                    </label>
                    <input type="number" name="block_threshold" value="{{ old('block_threshold', $settings->block_threshold) }}" 
                           min="3" max="50" required
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-1 text-xs text-gray-500">Jumlah percobaan gagal sebelum IP diblokir</p>
                    @error('block_threshold')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Block Duration (hours)
                    </label>
                    <input type="number" name="block_duration_hours" value="{{ old('block_duration_hours', $settings->block_duration_hours) }}" 
                           min="1" max="168" required
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-1 text-xs text-gray-500">Durasi pemblokiran otomatis (1-168 jam / 1-7 hari)</p>
                    @error('block_duration_hours')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        IP Whitelist
                    </label>
                    <textarea name="ip_whitelist" rows="5" 
                              class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm"
                              placeholder="Satu IP per baris&#10;192.168.1.1&#10;10.0.0.1">{{ old('ip_whitelist', $settings->ip_whitelist) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">IP yang tidak akan pernah diblokir (satu per baris)</p>
                    @error('ip_whitelist')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        IP Blacklist
                    </label>
                    <textarea name="ip_blacklist" rows="5" 
                              class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm"
                              placeholder="Satu IP per baris&#10;192.168.1.100&#10;10.0.0.100">{{ old('ip_blacklist', $settings->ip_blacklist) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">IP yang selalu diblokir (satu per baris)</p>
                    @error('ip_blacklist')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-admin.card>
    </div>

    <!-- Security Features -->
    <x-admin.card class="mt-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Fitur Keamanan</h3>
            <p class="text-sm text-gray-600">Aktifkan atau nonaktifkan fitur keamanan</p>
        </div>

        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="enable_suspicious_blocking" id="enable_suspicious_blocking" value="1"
                           {{ old('enable_suspicious_blocking', $settings->enable_suspicious_blocking) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                </div>
                <div class="ml-3">
                    <label for="enable_suspicious_blocking" class="font-medium text-gray-900">
                        Enable Suspicious Request Blocking
                    </label>
                    <p class="text-sm text-gray-500">Blokir otomatis request yang mencurigakan (SQL injection, XSS, dll)</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="enable_rate_limiting" id="enable_rate_limiting" value="1"
                           {{ old('enable_rate_limiting', $settings->enable_rate_limiting) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                </div>
                <div class="ml-3">
                    <label for="enable_rate_limiting" class="font-medium text-gray-900">
                        Enable Rate Limiting
                    </label>
                    <p class="text-sm text-gray-500">Aktifkan pembatasan jumlah request</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="log_security_events" id="log_security_events" value="1"
                           {{ old('log_security_events', $settings->log_security_events) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                </div>
                <div class="ml-3">
                    <label for="log_security_events" class="font-medium text-gray-900">
                        Log Security Events
                    </label>
                    <p class="text-sm text-gray-500">Catat semua event keamanan ke log file</p>
                </div>
            </div>
        </div>
    </x-admin.card>

    <!-- Submit Button -->
    <div class="mt-6 flex gap-3">
        <x-admin.button type="submit">
            Simpan Pengaturan
        </x-admin.button>
        <x-admin.button href="{{ route('admin.dashboard') }}" variant="secondary">
            Batal
        </x-admin.button>
    </div>
</form>

<!-- Recent Blocked IPs -->
@if($recentBlocks->count() > 0)
<x-admin.card class="mt-6">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-2">IP Terblokir Terbaru</h3>
        <p class="text-sm text-gray-600">10 IP yang baru saja diblokir</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attempts</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Blocked Until</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentBlocks as $block)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                        {{ $block->ip_address }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $block->reason ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $block->attempts }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($block->is_permanent)
                        <span class="text-red-600 font-medium">Permanent</span>
                        @else
                        {{ $block->blocked_until ? $block->blocked_until->format('d M Y H:i') : '-' }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($block->is_permanent)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Permanent
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Temporary
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.settings.blocked-ips') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
            Lihat Semua IP Terblokir →
        </a>
    </div>
</x-admin.card>
@endif
@endsection
