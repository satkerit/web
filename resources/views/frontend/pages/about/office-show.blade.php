<x-frontend-layout>
    <x-slot name="title">{{ $office->name }} - Lokasi Kantor</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 pt-32 pb-20 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

        <!-- Animated Gradient Orbs -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-sm mb-6 text-emerald-100/80 overflow-x-auto whitespace-nowrap pb-2">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('about.offices') }}" class="hover:text-white transition-colors">Kantor Kami</a>
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white font-medium">{{ $office->name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        @php
                            $badgeColors = [
                                'pusat' => 'bg-amber-400/90 text-amber-900 border-amber-400',
                                'cabang' => 'bg-blue-400/90 text-blue-900 border-blue-400',
                                'kas' => 'bg-emerald-400/90 text-emerald-900 border-emerald-400',
                                'kas_keliling' => 'bg-teal-400/90 text-teal-900 border-teal-400'
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm font-bold rounded-full border shadow-sm backdrop-blur-sm {{ $badgeColors[$office->type] ?? 'bg-gray-200 text-gray-800' }}">
                            {{ $office->type_label }}
                        </span>
                        @if($office->has_coordinates)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-white backdrop-blur-sm border border-white/20">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Terverifikasi
                        </span>
                        @endif
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-2">{{ $office->name }}</h1>
                    <p class="text-emerald-100 text-lg max-w-2xl">{{ $office->address }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 -mt-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Photo -->
                    @if($office->photo)
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100 group">
                        <div class="relative overflow-hidden">
                            <img src="{{ \App\Helpers\StorageHelper::url($office->photo) }}" alt="{{ $office->name }}" class="w-full h-auto max-h-[500px] object-cover transform group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60"></div>
                        </div>
                    </div>
                    @endif

                    <!-- Map -->
                    @if($office->has_coordinates)
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                Lokasi di Peta
                            </h2>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wide">Google Maps</span>
                        </div>
                        <div class="aspect-video relative z-0">
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
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                Buka di Google Maps
                            </a>
                            <a href="{{ $office->directions_url }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">
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
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Tentang Kantor Ini
                            </h2>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="prose prose-emerald max-w-none text-gray-600">
                                {!! nl2br(e($office->description)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Operational Hours -->
                    @if($office->operational_hours && count($office->operational_hours) > 0)
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Jam Operasional
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach($office->operational_hours as $day => $hours)
                                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full {{ $hours === 'Tutup' || $hours === 'Libur' ? 'bg-red-400' : 'bg-emerald-400' }}"></span>
                                        <span class="font-semibold text-gray-700">{{ $day }}</span>
                                    </div>
                                    <span class="text-gray-600 font-medium {{ $hours === 'Tutup' || $hours === 'Libur' ? 'text-red-500' : 'text-emerald-600' }}">{{ $hours }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Contact Info Card -->
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">Informasi Kontak</h3>

                        <div class="space-y-6">
                            <!-- Address -->
                            <div class="flex items-start group">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Alamat</p>
                                    <p class="text-gray-700 mt-1 leading-relaxed">{{ $office->address }}</p>
                                </div>
                            </div>

                            <!-- Phone -->
                            @if($office->phone)
                            <div class="flex items-start group">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Telepon</p>
                                    <a href="tel:{{ $office->phone }}" class="text-emerald-600 hover:text-emerald-700 font-bold mt-1 block">{{ $office->phone }}</a>
                                </div>
                            </div>
                            @endif

                            <!-- Email -->
                            @if($office->email)
                            <div class="flex items-start group">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Email</p>
                                    <a href="mailto:{{ $office->email }}" class="text-emerald-600 hover:text-emerald-700 font-medium mt-1 block">{{ $office->email }}</a>
                                </div>
                            </div>
                            @endif

                            <!-- GPS Coordinates -->
                            @if($office->has_coordinates)
                            <div class="flex items-start group">
                                <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Koordinat GPS</p>
                                    <p class="text-gray-700 mt-1 font-mono text-sm bg-gray-50 px-2 py-1 rounded border border-gray-200">{{ $office->latitude }}, {{ $office->longitude }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- CTA Buttons -->
                        <div class="mt-8 pt-6 border-t border-gray-100"></div>
                    </div>

                    <!-- Other Offices -->
                    @if($otherOffices->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center justify-between">
                            Kantor Lainnya
                            <a href="{{ route('about.offices') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat Semua</a>
                        </h3>
                        <div class="space-y-4">
                            @foreach($otherOffices as $other)
                            <a href="{{ route('about.offices.show', $other) }}" class="block p-3 rounded-xl hover:bg-gray-50 transition group border border-transparent hover:border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 border border-gray-100 group-hover:shadow-md transition-all">
                                        @if($other->photo)
                                        <img src="{{ \App\Helpers\StorageHelper::url($other->photo) }}" alt="{{ $other->name }}" class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-emerald-600 truncate transition-colors">{{ $other->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $other->type_label }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Back -->
            <div class="mt-12 border-t border-gray-200 pt-8">
                <a href="{{ route('about.offices') }}" class="inline-flex items-center text-gray-600 hover:text-emerald-600 font-medium transition group">
                    <div class="w-8 h-8 rounded-full bg-gray-100 group-hover:bg-emerald-100 flex items-center justify-center mr-3 transition-colors">
                        <svg class="w-4 h-4 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    Kembali ke Daftar Kantor
                </a>
            </div>
        </div>
    </section>
</x-frontend-layout>
