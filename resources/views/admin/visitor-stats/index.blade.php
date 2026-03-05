@extends('layouts.admin')

@section('title', 'Statistik Pengunjung')

@section('content')
<x-admin.page-header title="Statistik Pengunjung" subtitle="Analisis data pengunjung website">
    <x-slot:actions>
        <form method="GET" class="flex items-center gap-2" x-data="{ period: '{{ $period }}' }">
            <div class="flex items-center gap-2">
                <select name="period" x-model="period" @change="period !== 'custom' ? $el.form.submit() : null"
                        class="rounded-xl border-0 py-2 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="today">Hari Ini</option>
                    <option value="7days">7 Hari Terakhir</option>
                    <option value="30days">30 Hari Terakhir</option>
                    <option value="90days">90 Hari Terakhir</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="last_month">Bulan Lalu</option>
                    <option value="custom">Pilih Tanggal</option>
                </select>
            </div>

            <div x-show="period === 'custom'" class="flex items-center gap-2" x-transition style="display: none;">
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                       class="rounded-xl border-0 py-2 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-500 text-sm">
                <span class="text-slate-400">-</span>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                       class="rounded-xl border-0 py-2 px-4 text-slate-900 bg-white shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-500 text-sm">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition shadow-sm">
                    Filter
                </button>
            </div>
        </form>
    </x-slot:actions>
</x-admin.page-header>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Kunjungan</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_visits']) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Pengunjung Unik</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['unique_visitors']) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
   <div>
                <p class="text-sm font-medium text-slate-500">Kunjungan Hari Ini</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['today_visits']) }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Unik Hari Ini</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['today_unique']) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<x-admin.card title="Grafik Kunjungan" class="mb-6">
    <div class="h-80">
        <canvas id="visitsChart"></canvas>
    </div>
</x-admin.card>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Top Pages -->
    <x-admin.card title="Halaman Populer" :noPadding="true">
        <div class="divide-y divide-slate-100">
            @forelse($topPages as $index => $page)
            <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-100 text-slate-600 text-xs font-medium flex items-center justify-center">
                        {{ $index + 1 }}
                    </span>
                    <span class="text-sm text-slate-700 truncate" title="{{ $page->url }}">
                        {{ Str::limit(parse_url($page->url, PHP_URL_PATH) ?: '/', 40) }}
                    </span>
                </div>
                <span class="text-sm font-semibold text-slate-900">{{ number_format($page->visits) }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-500">Belum ada data</div>
            @endforelse
        </div>
    </x-admin.card>

    <!-- Countries -->
    <x-admin.card title="Negara Pengunjung" :noPadding="true">
        <div class="divide-y divide-slate-100">
            @forelse($countries as $country)
            <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-3">
                    <span class="text-lg">🌍</span>
                    <span class="text-sm text-slate-700">{{ $country->country }}</span>
                </div>
                <span class="text-sm font-semibold text-slate-900">{{ number_format($country->total) }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-500">Belum ada data</div>
            @endforelse
        </div>
    </x-admin.card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Browsers -->
    <x-admin.card title="Browser">
        <div class="space-y-3">
            @forelse($browsers as $browser)
            @php
                $percentage = $stats['total_visits'] > 0 ? ($browser->total / $stats['total_visits']) * 100 : 0;
            @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-slate-700">{{ $browser->browser }}</span>
                    <span class="text-slate-500">{{ number_format($percentage, 1) }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-500 py-4">Belum ada data</p>
            @endforelse
        </div>
    </x-admin.card>

    <!-- Devices -->
    <x-admin.card title="Perangkat">
        <div class="space-y-3">
            @forelse($devices as $device)
            @php
                $percentage = $stats['total_visits'] > 0 ? ($device->total / $stats['total_visits']) * 100 : 0;
                $icon = match($device->device_type) {
                    'mobile' => '📱',
                    'tablet' => '📲',
                    default => '💻'
                };
            @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-slate-700">{{ $icon }} {{ ucfirst($device->device_type) }}</span>
                    <span class="text-slate-500">{{ number_format($percentage, 1) }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-500 py-4">Belum ada data</p>
            @endforelse
        </div>
    </x-admin.card>

    <!-- Platforms -->
    <x-admin.card title="Sistem Operasi">
        <div class="space-y-3">
            @forelse($platforms as $platform)
            @php
                $percentage = $stats['total_visits'] > 0 ? ($platform->total / $stats['total_visits']) * 100 : 0;
            @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-slate-700">{{ $platform->platform }}</span>
                    <span class="text-slate-500">{{ number_format($percentage, 1) }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-500 py-4">Belum ada data</p>
            @endforelse
        </div>
    </x-admin.card>
</div>

<!-- Recent Visitors -->
<x-admin.card title="Pengunjung Terbaru" :noPadding="true">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">IP</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Perangkat</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Browser</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Halaman</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentVisitors as $visitor)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="text-sm text-slate-900">{{ $visitor->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-slate-500">{{ $visitor->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <span class="text-sm font-mono text-slate-700">{{ $visitor->ip_address }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="text-sm text-slate-900">{{ $visitor->city ?? '-' }}</div>
                        <div class="text-xs text-slate-500">{{ $visitor->country ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium
                            {{ $visitor->device_type == 'mobile' ? 'bg-blue-100 text-blue-700' : ($visitor->device_type == 'tablet' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700') }}">
                            {{ ucfirst($visitor->device_type ?? 'Unknown') }}
                        </span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="text-sm text-slate-900">{{ $visitor->browser ?? '-' }}</div>
                        <div class="text-xs text-slate-500">{{ $visitor->platform ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-3">
                        <span class="text-sm text-slate-700 truncate block max-w-xs" title="{{ $visitor->url }}">
                            {{ Str::limit(parse_url($visitor->url, PHP_URL_PATH) ?: '/', 30) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data pengunjung</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.card>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('visitsChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const data = @js($visitsPerDay);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'Total Kunjungan',
                data: data.map(d => d.total),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3
            }, {
                label: 'Pengunjung Unik',
                data: data.map(d => d.unique_visitors),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush
