<x-frontend-layout>
    <x-slot name="title">{{ $office->name }} - Lokasi Kantor</x-slot>

    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-500 to-teal-500 pt-32 pb-20">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <nav class="text-sm mb-4">
                <a href="{{ route('home') }}" class="text-white/70 hover:text-white">Beranda</a>
                <span class="mx-2 text-white/50">/</span>
                <a href="{{ route('about.offices') }}" class="text-white/70 hover:text-white">Kantor Kami</a>
                <span class="mx-2 text-white/50">/</span>
                <span class="text-white">{{ $office->name }}</span>
            </nav>
            <div class="flex items-center gap-3 mb-4">
                @php
                    $badgeColors = [
                        'pusat' => 'bg-amber-400 text-amber-900',
                        'cabang' => 'bg-blue-400 text-blue-900',
                        'kas' => 'bg-gray-200 text-gray-800',
                        'kas_keliling' => 'bg-teal-400 text-teal-900'
                    ];
                @endphp
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $badgeColors[$office->type] ?? 'bg-gray-200 text-gray-800' }}">
                    {{ $office->type_label }}
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $office->name }}</h1>
        </div>
    </section>

    <section class="py-12 -mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Photo -->
                    @if($office->photo)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <img src="{{ \App\Helpers\StorageHelper::url($office->photo) }}" alt="{{ $office->name }}" class="w-full h-auto max-h-[400px] object-contain bg-gray-100 rounded-lg">
                    </div>
                    @endif

                    <!-- Map -->
                    @if($office->has_coordinates)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <span class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                Lokasi di Peta
                            </h2>
                        </div>
                        <div class="aspect-video">
                            <iframe
                                src="https://maps.google.com/maps?q={{ $office->latitude }},{{ $office->longitude }}&z=16&output=embed"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="p-4 bg-gray-50 flex flex-wrap gap-3">
                            <a href="https://www.google.com/maps?q={{ $office->latitude }},{{ $office->longitude }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                Buka di Google Maps
                            </a>
                            <a href="{{ $office->directions_url }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Petunjuk Arah
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Description -->
                    @if($office->description)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </span>
                            Tentang Kantor Ini
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($office->description)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Operational Hours -->
                    @if($office->operational_hours && count($office->operational_hours) > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <span class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Jam Operasional
                        </h2>
                        <div class="space-y-2">
                            @foreach($office->operational_hours as $day => $hours)
                            <div class="flex justify-between py-2 border-b border-gray-100 last:border-0">
                                <span class="font-medium text-gray-700">{{ $day }}</span>
                                <span class="text-gray-600">{{ $hours }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Contact Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Kontak</h3>

                        <div class="space-y-4">
                            <!-- Address -->
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Alamat</p>
                                    <p class="text-gray-700 mt-1">{{ $office->address }}</p>
                                </div>
                            </div>

                            <!-- Phone -->
                            @if($office->phone)
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Telepon</p>
                                    <a href="tel:{{ $office->phone }}" class="text-emerald-600 hover:text-emerald-700 font-medium mt-1 block">{{ $office->phone }}</a>
                                </div>
                            </div>
                            @endif

                            <!-- Email -->
                            @if($office->email)
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Email</p>
                                    <a href="mailto:{{ $office->email }}" class="text-emerald-600 hover:text-emerald-700 font-medium mt-1 block">{{ $office->email }}</a>
                                </div>
                            </div>
                            @endif

                            <!-- GPS Coordinates -->
                            @if($office->has_coordinates)
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Koordinat GPS</p>
                                    <p class="text-gray-700 mt-1 font-mono text-sm">{{ $office->latitude }}, {{ $office->longitude }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- CTA Buttons -->
                        <div class="mt-6 space-y-3">

                            @if($office->has_coordinates)
                            <a href="{{ $office->directions_url }}"
                               target="_blank"
                               class="flex items-center justify-center w-full px-4 py-3 border-2 border-emerald-600 text-emerald-600 rounded-xl font-medium hover:bg-emerald-50 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Petunjuk Arah
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Other Offices -->
                    @if($otherOffices->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kantor Lainnya</h3>
                        <div class="space-y-3">
                            @foreach($otherOffices as $other)
                            <a href="{{ route('about.offices.show', $other) }}" class="block p-3 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                        @if($other->photo)
                                        <img src="{{ \App\Helpers\StorageHelper::url($other->photo) }}" alt="{{ $other->name }}" class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-emerald-600 truncate">{{ $other->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $other->type_label }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <a href="{{ route('about.offices') }}" class="block mt-4 text-center text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                            Lihat Semua Kantor →
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Back -->
            <div class="mt-12">
                <a href="{{ route('about.offices') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Kantor
                </a>
            </div>
        </div>
    </section>
</x-frontend-layout>
