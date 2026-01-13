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

    <!-- Hero -->
    <section class="relative pt-28 pb-16 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.03\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-emerald-300 text-sm font-medium mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Kontak
            </span>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Hubungi Kami</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">Kami siap membantu Anda. Temukan kantor terdekat atau kirim pesan kepada kami.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 -mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Quick Contact Cards -->
            <div class="flex flex-wrap justify-center gap-4 mb-10">
                <a href="tel:{{ $companyInfo->phone ?? '' }}" class="bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition-all group border border-gray-100 w-full sm:w-auto sm:min-w-[200px]">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform mx-auto sm:mx-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 mb-1 text-center sm:text-left">Telepon</p>
                    <p class="text-sm font-semibold text-gray-900 truncate text-center sm:text-left">{{ $companyInfo->phone ?? '-' }}</p>
                </a>

                <a href="mailto:{{ $companyInfo->email ?? '' }}" class="bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition-all group border border-gray-100 w-full sm:w-auto sm:min-w-[200px]">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform mx-auto sm:mx-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 mb-1 text-center sm:text-left">Email</p>
                    <p class="text-sm font-semibold text-gray-900 truncate text-center sm:text-left">{{ $companyInfo->email ?? '-' }}</p>
                </a>

                <div class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 w-full sm:w-auto sm:min-w-[200px]">
                    <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-500 rounded-lg flex items-center justify-center mb-3 mx-auto sm:mx-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 mb-1 text-center sm:text-left">Jam Operasional</p>
                    <p class="text-sm font-semibold text-gray-900 text-center sm:text-left">Sen-Jum 08:00-16:00</p>
                </div>
            </div>

            <!-- Map & Offices Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10" x-data="officeMap()">
                <!-- Interactive Map -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Peta Lokasi Kantor
                        </h2>
                        <span class="text-xs text-gray-500">{{ $officesWithCoords->count() }} lokasi</span>
                    </div>
                    <div id="officeMap" class="h-[400px] lg:h-[500px]"></div>
                </div>

                <!-- Office List Sidebar -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 flex flex-col max-h-[500px]">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-900 mb-3">Daftar Kantor</h2>
                        <!-- Filter Tabs -->
                        <div class="flex flex-wrap gap-1.5">
                            <button @click="filterType = 'all'" :class="filterType === 'all' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition">
                                Semua
                            </button>
                            <button @click="filterType = 'pusat'" :class="filterType === 'pusat' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition">
                                Pusat
                            </button>
                            <button @click="filterType = 'cabang'" :class="filterType === 'cabang' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition">
                                Cabang
                            </button>
                            <button @click="filterType = 'kas'" :class="filterType === 'kas' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition">
                                Kas
                            </button>
                        </div>
                    </div>
                    <!-- Office List -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-2">
                        @foreach($offices as $office)
                        <div x-show="filterType === 'all' || filterType === '{{ $office->type }}'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             @click="selectOffice({{ $office->id }}, {{ $office->latitude ?? 'null' }}, {{ $office->longitude ?? 'null' }})"
                             :class="selectedOffice === {{ $office->id }} ? 'ring-2 ring-emerald-500 bg-emerald-50' : 'hover:bg-gray-50'"
                             class="p-3 rounded-xl border border-gray-100 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                @php
                                    $typeColors = [
                                        'pusat' => 'bg-amber-500',
                                        'cabang' => 'bg-blue-500',
                                        'kas' => 'bg-gray-700',
                                        'kas_keliling' => 'bg-teal-600'
                                    ];
                                @endphp
                                <div class="w-8 h-8 {{ $typeColors[$office->type] ?? 'bg-gray-500' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $office->name }}</h3>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ $office->address }}</p>
                                    @if($office->phone)
                                    <p class="text-xs text-emerald-600 mt-1">{{ $office->phone }}</p>
                                    @endif
                                </div>
                                @if($office->has_coordinates)
                                <span class="w-5 h-5 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0" title="Lihat di peta">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Form Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Company Info -->
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            Kantor Pusat
                        </h3>
                        <div class="space-y-3 text-sm">
                            <p class="text-gray-600 leading-relaxed">{!! nl2br(e($companyInfo->address ?? 'Alamat belum tersedia')) !!}</p>
                            @if($companyInfo->phone)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $companyInfo->phone }}
                            </div>
                            @endif
                            @if($companyInfo->email)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $companyInfo->email }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Social Media -->
                    @if($companyInfo->facebook || $companyInfo->instagram || $companyInfo->twitter || $companyInfo->youtube)
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4">Ikuti Kami</h3>
                        <div class="flex flex-wrap gap-2">
                            @if($companyInfo->facebook)
                            <a href="{{ $companyInfo->facebook }}" target="_blank" class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            @endif
                            @if($companyInfo->instagram)
                            <a href="{{ $companyInfo->instagram }}" target="_blank" class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-500 rounded-lg flex items-center justify-center text-white hover:opacity-90 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            @endif
                            @if($companyInfo->youtube)
                            <a href="{{ $companyInfo->youtube }}" target="_blank" class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center text-white hover:bg-red-700 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-xl border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Kirim Pesan</h2>
                        <p class="text-gray-600 text-sm mb-6">Isi form di bawah ini dan kami akan segera menghubungi Anda</p>
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
                                        <p class="text-xs text-gray-600 mt-1">${office.address}</p>
                                        ${office.phone ? `<p class="text-xs text-emerald-600 mt-1">${office.phone}</p>` : ''}
                                        ${office.directions_url ? `<a href="${office.directions_url}" target="_blank" class="inline-flex items-center mt-2 text-xs text-blue-600 hover:underline">
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
                }
            }
        }
    </script>
    @endpush
</x-frontend-layout>
