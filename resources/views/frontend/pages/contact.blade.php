<x-frontend-layout>
    <x-slot name="title">Hubungi Kami - {{ $companyInfo->name ?? 'BPRS Bangka Belitung' }}</x-slot>

    @php
        $offices = \App\Models\Office::active()
            ->orderByRaw("CASE type WHEN 'pusat' THEN 1 WHEN 'cabang' THEN 2 WHEN 'kas' THEN 3 WHEN 'kas_keliling' THEN 4 ELSE 5 END")
            ->get();
        $officesWithCoords = $offices->filter(fn($o) => $o->has_coordinates);
        $centerLat = $officesWithCoords->avg('latitude') ?? -2.1316;
        $centerLng = $officesWithCoords->avg('longitude') ?? 106.1169;

        // Prepare offices data for JavaScript
        $officesJson = $offices->map(function($o) {
            return [
                'id' => $o->id,
                'name' => $o->name,
                'type' => $o->type,
                'type_label' => $o->type_label,
                'address' => $o->address,
                'phone' => $o->phone,
                'lat' => $o->latitude,
                'lng' => $o->longitude,
                'has_coords' => $o->has_coordinates,
                'directions_url' => $o->directions_url
            ];
        });
    @endphp

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-700 via-primary-500 to-primary-600 py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/15 backdrop-blur-sm rounded-full text-white text-sm font-semibold mb-6 border border-white/10">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Kontak Kami
            </span>
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">Hubungi Kami</h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto leading-relaxed">Kami siap membantu Anda dengan layanan perbankan syariah terbaik. Temukan kantor terdekat atau kirim pesan kepada kami.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 md:py-20 bg-gray-50 -mt-10 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Quick Contact Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <a href="tel:{{ $companyInfo->phone ?? '' }}" class="bg-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 group border border-gray-100 hover:border-primary-200 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-sm font-medium text-gray-500 mb-1">Telepon</p>
                        <p class="text-lg font-bold text-gray-900 truncate">{{ $companyInfo->phone ?? '-' }}</p>
                    </div>
                </a>

                <a href="mailto:{{ $companyInfo->email ?? '' }}" class="bg-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 group border border-gray-100 hover:border-primary-200 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-sm font-medium text-gray-500 mb-1">Email</p>
                        <p class="text-lg font-bold text-gray-900 truncate">{{ $companyInfo->email ?? '-' }}</p>
                    </div>
                </a>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-sm font-medium text-gray-500 mb-1">Jam Operasional</p>
                        <p class="text-lg font-bold text-gray-900">Sen-Jum 08:00-16:00</p>
                    </div>
                </div>
            </div>

            <!-- Map & Offices Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12" x-data="officeMap()">
                <!-- Interactive Map -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 h-[500px] flex flex-col">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="font-bold text-gray-900 flex items-center text-lg">
                            <svg class="w-6 h-6 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Peta Lokasi Kantor
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 bg-primary-100 text-primary-700 text-xs font-bold rounded-full border border-primary-200">
                            {{ $officesWithCoords->count() }} Lokasi
                        </span>
                    </div>
                    <div id="officeMap" class="flex-1 w-full bg-gray-100"></div>
                </div>

                <!-- Office List Sidebar -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 flex flex-col h-[500px] overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-bold text-gray-900 mb-4 text-lg">Daftar Kantor</h2>
                        <!-- Filter Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <button @click="filterType = 'all'" :class="filterType === 'all' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                                Semua
                            </button>
                            <button @click="filterType = 'pusat'" :class="filterType === 'pusat' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                                Pusat
                            </button>
                            <button @click="filterType = 'cabang'" :class="filterType === 'cabang' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                                Cabang
                            </button>
                            <button @click="filterType = 'kas'" :class="filterType === 'kas' ? 'bg-gray-700 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                                Kas
                            </button>
                        </div>
                    </div>
                    <!-- Office List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                        @foreach($offices as $office)
                        <div x-show="filterType === 'all' || filterType === '{{ $office->type }}'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             @click="selectOffice({{ $office->id }}, {{ $office->latitude ?? 'null' }}, {{ $office->longitude ?? 'null' }})"
                             :class="selectedOffice === {{ $office->id }} ? 'ring-2 ring-primary-500 bg-primary-50 border-primary-200' : 'hover:bg-gray-50 border-gray-100'"
                             class="p-4 rounded-xl border cursor-pointer transition-all duration-200 group">
                            <div class="flex items-start gap-4">
                                @php
                                    $typeColors = [
                                        'pusat' => 'bg-amber-500',
                                        'cabang' => 'bg-blue-500',
                                        'kas' => 'bg-gray-700',
                                        'kas_keliling' => 'bg-teal-600'
                                    ];
                                @endphp
                                <div class="w-10 h-10 {{ $typeColors[$office->type] ?? 'bg-gray-500' }} rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm text-white font-bold group-hover:scale-110 transition-transform">
                                    {{ substr($office->type_label, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 truncate">{{ $office->name }}</h3>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-1 leading-relaxed">{{ $office->address }}</p>
                                    @if($office->phone)
                                    <p class="text-xs text-primary-600 mt-2 font-medium flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $office->phone }}
                                    </p>
                                    @endif
                                </div>
                                @if($office->has_coordinates)
                                <span class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-primary-100 transition-colors" title="Lihat di peta">
                                    <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Form Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div class="space-y-8">
                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-6 flex items-center text-lg">
                            <span class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            Kantor Pusat
                        </h3>
                        <div class="space-y-4 text-sm">
                            <p class="text-gray-600 leading-relaxed font-medium text-base">{!! nl2br(e($companyInfo->address ?? 'Alamat belum tersedia')) !!}</p>

                            <div class="pt-4 border-t border-gray-100 space-y-3">
                                @if($companyInfo->phone)
                                <div class="flex items-center text-gray-600 group hover:text-primary-600 transition-colors cursor-pointer">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <span class="font-medium">{{ $companyInfo->phone }}</span>
                                </div>
                                @endif
                                @if($companyInfo->email)
                                <div class="flex items-center text-gray-600 group hover:text-primary-600 transition-colors cursor-pointer">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="font-medium">{{ $companyInfo->email }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    @if($companyInfo->facebook || $companyInfo->instagram || $companyInfo->twitter || $companyInfo->youtube)
                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-6 text-lg">Ikuti Kami</h3>
                        <div class="flex flex-wrap gap-3">
                            @if($companyInfo->facebook)
                            <a href="{{ $companyInfo->facebook }}" target="_blank" class="w-12 h-12 bg-[#1877F2] rounded-xl flex items-center justify-center text-white hover:scale-110 transition-transform shadow-lg shadow-blue-500/30">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            @endif
                            @if($companyInfo->instagram)
                            <a href="{{ $companyInfo->instagram }}" target="_blank" class="w-12 h-12 bg-gradient-to-br from-[#833AB4] via-[#FD1D1D] to-[#F77737] rounded-xl flex items-center justify-center text-white hover:scale-110 transition-transform shadow-lg shadow-pink-500/30">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            @endif
                            @if($companyInfo->youtube)
                            <a href="{{ $companyInfo->youtube }}" target="_blank" class="w-12 h-12 bg-[#FF0000] rounded-xl flex items-center justify-center text-white hover:scale-110 transition-transform shadow-lg shadow-red-500/30">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-xl border border-gray-100">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Kirim Pesan</h2>
                            <p class="text-gray-600">Isi form di bawah ini dan tim kami akan segera menghubungi Anda dalam waktu 24 jam kerja.</p>
                        </div>
                        <livewire:frontend.contact.form />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leaflet CSS & JS -->
    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        .leaflet-popup-content-wrapper { border-radius: 12px; }
        .leaflet-popup-content { margin: 12px 16px; }
        .custom-marker { background: none; border: none; }
    </style>
    <script>
        function officeMap() {
            return {
                filterType: 'all',
                selectedOffice: null,
                map: null,
                markers: {},
                offices: @json($officesJson),
                init() {
                    this.$nextTick(() => {
                        this.initMap();
                    });
                },
                initMap() {
                    const centerLat = {{ $centerLat }};
                    const centerLng = {{ $centerLng }};

                    this.map = L.map('officeMap').setView([centerLat, centerLng], 10);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(this.map);

                    const typeColors = {
                        'pusat': '#f59e0b',
                        'cabang': '#3b82f6',
                        'kas': '#374151',
                        'kas_keliling': '#0d9488'
                    };

                    this.offices.forEach(office => {
                        if (office.has_coords) {
                            const color = typeColors[office.type] || '#10b981';
                            const icon = L.divIcon({
                                className: 'custom-marker',
                                html: `<div style="background:${color};width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.3);border:3px solid white;">
                                    <svg width="16" height="16" fill="white" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                </div>`,
                                iconSize: [32, 32],
                                iconAnchor: [16, 32]
                            });

                            const marker = L.marker([office.lat, office.lng], { icon })
                                .addTo(this.map)
                                .bindPopup(`
                                    <div class="min-w-[200px]">
                                        <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-full text-white mb-2" style="background:${color}">${office.type_label}</span>
                                        <h3 class="font-bold text-gray-900 text-sm">${office.name}</h3>
                                        <p class="text-xs text-gray-600 mt-1 leading-normal mb-2">${office.address}</p>
                                        ${office.phone ? `<p class="text-xs text-emerald-600 font-bold mb-2">${office.phone}</p>` : ''}
                                        ${office.directions_url ? `<a href="${office.directions_url}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors w-full justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                            Petunjuk Arah
                                        </a>` : ''}
                                    </div>
                                `);

                            this.markers[office.id] = marker;
                        }
                    });
                },
                selectOffice(id, lat, lng) {
                    this.selectedOffice = id;
                    if (lat && lng && this.map) {
                        this.map.setView([lat, lng], 15);
                        if (this.markers[id]) {
                            this.markers[id].openPopup();
                        }
                    }
                    // Smooth scroll to map on mobile
                    if (window.innerWidth < 1024) {
                        document.getElementById('officeMap').scrollIntoView({ behavior: 'smooth' });
                    }
                }
            }
        }
    </script>
    @endpush
</x-frontend-layout>
