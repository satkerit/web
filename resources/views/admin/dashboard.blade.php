@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Welcome Card --}}
    <div class="relative overflow-hidden bg-slate-900 rounded-3xl p-8 text-white shadow-xl ring-1 ring-white/10">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-emerald-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-blue-500/20 blur-3xl"></div>

        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight mb-2">Selamat Datang, <span class="text-emerald-400">{{ auth()->user()->name }}</span>!</h1>
            <p class="text-slate-400/90 text-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Berita Stats -->
        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-900/5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Berita</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">{{ \App\Models\News::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1 text-xs font-medium text-blue-600">
                <span>View Details</span>
                <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

        <!-- Produk Stats -->
        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-900/5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Produk</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">{{ \App\Models\Product::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-600 transition-colors duration-300">
                    <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1 text-xs font-medium text-emerald-600">
                <span>View Details</span>
                <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

        <!-- Lelang Stats -->
        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-900/5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Lelang Aktif</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight group-hover:text-amber-600 transition-colors">{{ \App\Models\Auction::where('status', 'upcoming')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center group-hover:bg-amber-500 transition-colors duration-300">
                    <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1 text-xs font-medium text-amber-600">
                <span>View Details</span>
                <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

        <!-- Pengaduan Stats -->
        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-900/5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Pengaduan Baru</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight group-hover:text-red-600 transition-colors">{{ \App\Models\Complaint::where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center group-hover:bg-red-500 transition-colors duration-300">
                    <svg class="w-6 h-6 text-red-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1 text-xs font-medium text-red-600">
                <span>View Details</span>
                <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>
    </div>

    {{-- Visitor Statistics Chart --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Statistik Pengunjung</h2>
                <p class="text-sm text-slate-500">7 hari terakhir</p>
            </div>
            <a href="{{ route('admin.visitor-stats.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-1">
                Lihat Detail
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Mini Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-xl p-4">
                <p class="text-xs font-medium text-blue-600 mb-1">Hari Ini</p>
                <p class="text-2xl font-bold text-blue-700">{{ number_format($visitorStats['todayVisits']) }}</p>
                <p class="text-xs text-blue-500">kunjungan</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4">
                <p class="text-xs font-medium text-emerald-600 mb-1">Unik Hari Ini</p>
                <p class="text-2xl font-bold text-emerald-700">{{ number_format($visitorStats['todayUnique']) }}</p>
                <p class="text-xs text-emerald-500">pengunjung</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4">
                <p class="text-xs font-medium text-purple-600 mb-1">7 Hari</p>
                <p class="text-2xl font-bold text-purple-700">{{ number_format($visitorStats['weekTotal']) }}</p>
                <p class="text-xs text-purple-500">kunjungan</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-4">
                <p class="text-xs font-medium text-amber-600 mb-1">Unik 7 Hari</p>
                <p class="text-2xl font-bold text-amber-700">{{ number_format($visitorStats['weekUnique']) }}</p>
                <p class="text-xs text-amber-500">pengunjung</p>
            </div>
        </div>

        {{-- Chart --}}
        <div class="relative h-64">
            <canvas id="visitorChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent News --}}
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-900">Berita Terbaru</h2>
                <a href="{{ route('admin.news.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse(\App\Models\News::latest()->take(5)->get() as $news)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                        @if($news->featured_image)
                            <img src="{{ Storage::url($news->featured_image) }}" alt="" class="w-12 h-12 rounded-lg object-cover ring-1 ring-slate-200">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-slate-50 ring-1 ring-slate-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $news->title }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs text-slate-500">{{ $news->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if($news->is_published)
                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-600 rounded-lg ring-1 ring-emerald-500/10">Published</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold bg-amber-50 text-amber-600 rounded-lg ring-1 ring-amber-500/10">Draft</span>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <p class="text-slate-500 text-sm">Belum ada berita</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Complaints --}}
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-900">Pengaduan Terbaru</h2>
                <a href="{{ route('admin.complaints.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse(\App\Models\Complaint::latest()->take(5)->get() as $complaint)
                    <a href="{{ route('admin.complaints.show', $complaint) }}" class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50 transition-all group">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-xs font-mono text-slate-400 bg-slate-100 px-1.5 rounded">{{ $complaint->ticket_number }}</span>
                                <span class="text-sm font-medium text-slate-900 truncate group-hover:text-emerald-600 transition-colors">{{ $complaint->subject }}</span>
                            </div>
                            <p class="text-xs text-slate-500 truncate">{{ Str::limit($complaint->description, 60) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @switch($complaint->status)
                                @case('pending')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-amber-50 text-amber-600 rounded-lg ring-1 ring-amber-500/10">Menunggu</span>
                                    @break
                                @case('in_review')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-600 rounded-lg ring-1 ring-blue-500/10">Review</span>
                                    @break
                                @case('investigating')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-purple-50 text-purple-600 rounded-lg ring-1 ring-purple-500/10">Investigasi</span>
                                    @break
                                @case('resolved')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-green-50 text-green-600 rounded-lg ring-1 ring-green-500/10">Selesai</span>
                                    @break
                            @endswitch
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-slate-500 text-sm">Belum ada pengaduan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Info --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-6">Informasi Akun</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email Saat Ini</p>
                    <p class="font-medium text-slate-900">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Peran Akses</p>
                    <p class="font-medium text-slate-900">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Akun</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 ring-1 ring-emerald-500/20">
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($visitorStats['labels']),
            datasets: [
                {
                    label: 'Total Kunjungan',
                    data: @json($visitorStats['totalVisits']),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Pengunjung Unik',
                    data: @json($visitorStats['uniqueVisitors']),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(16, 185, 129)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 13 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11 },
                        color: '#64748b'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)'
                    },
                    ticks: {
                        font: { size: 11 },
                        color: '#64748b',
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
