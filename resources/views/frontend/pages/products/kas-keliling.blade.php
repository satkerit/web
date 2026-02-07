<x-frontend-layout>
    <x-slot name="title">Kas Keliling - {{ $companyInfo->name ?? 'BPRS Bangka Belitung' }}</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-teal-100 text-sm font-medium mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Layanan Mobile
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight">Kas Keliling</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">Layanan perbankan yang mendatangi Anda di lokasi-lokasi strategis</p>
        </div>
    </section>

    <section class="py-16 -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Info Cards -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Lokasi Strategis</h3>
                    <p class="text-gray-600 text-sm">Kami hadir di pasar, sekolah, dan tempat keramaian lainnya untuk memudahkan akses perbankan Anda.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Jadwal Teratur</h3>
                    <p class="text-gray-600 text-sm">Kas keliling beroperasi sesuai jadwal yang telah ditentukan agar Anda dapat merencanakan kunjungan.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-amber-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Layanan Lengkap</h3>
                    <p class="text-gray-600 text-sm">Setoran, penarikan, pembayaran, dan berbagai layanan perbankan lainnya tersedia di kas keliling.</p>
                </div>
            </div>

            <!-- Schedule Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Jadwal 5 Hari Terdekat</h2>
                        <p class="text-gray-600 mt-1">Temukan jadwal kas keliling di area Anda</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Jadwal dapat berubah sewaktu-waktu</span>
                    </div>
                </div>

                @if($schedulesByDate->count() > 0)
                <div class="space-y-8">
                    @foreach($schedulesByDate as $date => $schedules)
                    @php
                        $dateObj = \Carbon\Carbon::parse($date);
                        $isToday = $dateObj->isToday();
                        $isTomorrow = $dateObj->isTomorrow();
                    @endphp
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <!-- Date Header -->
                        <div class="px-6 py-4 {{ $isToday ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : ($isTomorrow ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : 'bg-gradient-to-r from-slate-600 to-slate-700') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-white/20 rounded-xl flex flex-col items-center justify-center">
                                        <span class="text-2xl font-bold text-white">{{ $dateObj->format('d') }}</span>
                                        <span class="text-xs text-white/80 uppercase">{{ $dateObj->translatedFormat('M') }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ $dateObj->translatedFormat('l') }}</h3>
                                        <p class="text-white/80 text-sm">{{ $dateObj->translatedFormat('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($isToday)
                                    <span class="px-4 py-1.5 bg-white text-emerald-600 text-sm font-bold rounded-full animate-pulse">
                                        HARI INI
                                    </span>
                                    @elseif($isTomorrow)
                                    <span class="px-4 py-1.5 bg-white text-blue-600 text-sm font-bold rounded-full">
                                        BESOK
                                    </span>
                                    @endif
                                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm font-medium">
                                        {{ $schedules->count() }} lokasi
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Schedules for this date -->
                        <div class="divide-y divide-gray-100">
                            @foreach($schedules as $schedule)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                                    <!-- Time & Location -->
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <!-- Time Badge -->
                                            <div class="flex-shrink-0 px-4 py-2 bg-emerald-50 rounded-xl border border-emerald-100">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-bold text-emerald-700">
                                                        {{ $schedule->time_range }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <!-- Location -->
                                                <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $schedule->location }}</h4>
                                                
                                                <!-- PIC -->
                                                @if($schedule->pic_name || $schedule->pic_phone)
                                                <p class="text-sm text-gray-500 flex items-center gap-2 mb-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $schedule->pic_name }}
                                                    @if($schedule->pic_phone)
                                                    <span class="text-gray-300">|</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    {{ $schedule->pic_phone }}
                                                    @endif
                                                </p>
                                                @endif

                                                @if($schedule->notes)
                                                <p class="text-sm text-gray-500 italic mt-2">{{ $schedule->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Facilities -->
                                    @if($schedule->facility)
                                    <div class="lg:w-96">
                                        <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            Fasilitas
                                        </h5>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($schedule->facility_list as $facility)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg border border-amber-200">
                                                {{ $facility }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Jadwal</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Tidak ada jadwal kas keliling dalam 5 hari ke depan. Silakan hubungi kami untuk informasi lebih lanjut.</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Hubungi Kami
                    </a>
                </div>
                @endif
            </div>

            <!-- CTA Section -->
            <div class="mt-12 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-8 md:p-12 text-center text-white">
                <h3 class="text-2xl md:text-3xl font-bold mb-4">Butuh Informasi Lebih Lanjut?</h3>
                <p class="text-white/90 max-w-2xl mx-auto mb-6">Hubungi kami untuk mengetahui jadwal kas keliling di lokasi Anda atau untuk permintaan layanan khusus.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Hubungi Kami
                    </a>
                    @if($companyInfo->phone ?? false)
                    <a href="tel:{{ $companyInfo->phone }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 text-white font-semibold rounded-xl hover:bg-white/30 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $companyInfo->phone }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
