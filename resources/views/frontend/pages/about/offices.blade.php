<x-frontend-layout>
    <x-slot name="title">Kantor Kami - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative py-20 md:py-24 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-6 ring-1 ring-white/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Jaringan Kantor
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">Kantor Kami</h1>
            <p class="text-xl text-emerald-50 max-w-2xl mx-auto">Temukan kantor BPRS Bangka Belitung terdekat dari lokasi Anda untuk kemudahan bertransaksi.</p>
        </div>
    </section>

    <section class="py-16 -mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-2 mb-8 border border-gray-100 max-w-4xl mx-auto">
                <div class="flex flex-wrap justify-center gap-2">
                    <a href="{{ route('about.offices') }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ !request('type') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 ring-2 ring-emerald-600 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-emerald-600' }}">
                        Semua Kantor
                    </a>
                    <a href="{{ route('about.offices', ['type' => 'pusat']) }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ request('type') === 'pusat' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30 ring-2 ring-amber-500 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-amber-500' }}">
                        Kantor Pusat
                    </a>
                    <a href="{{ route('about.offices', ['type' => 'cabang']) }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ request('type') === 'cabang' ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30 ring-2 ring-blue-500 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-blue-500' }}">
                        Kantor Cabang
                    </a>
                    <a href="{{ route('about.offices', ['type' => 'kas']) }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ request('type') === 'kas' ? 'bg-gray-700 text-white shadow-lg shadow-gray-700/30 ring-2 ring-gray-700 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-gray-700' }}">
                        Kantor Kas
                    </a>
                    <a href="{{ route('about.offices', ['type' => 'kas_keliling']) }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ request('type') === 'kas_keliling' ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/30 ring-2 ring-teal-600 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-teal-600' }}">
                        Kas Keliling
                    </a>
                </div>
            </div>

            @if($offices->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($offices as $office)
                <div class="group bg-white rounded-2xl shadow-xl shadow-gray-200/50 overflow-hidden hover:shadow-2xl hover:shadow-emerald-900/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="h-1.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    <div class="relative h-56 overflow-hidden">
                        @if($office->photo)
                        <img src="{{ \App\Helpers\StorageHelper::url($office->photo) }}" alt="{{ $office->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            @php
                                $badgeColors = [
                                    'pusat' => 'bg-amber-500 text-white',
                                    'cabang' => 'bg-blue-500 text-white',
                                    'kas' => 'bg-gray-700 text-white',
                                    'kas_keliling' => 'bg-teal-600 text-white'
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badgeColors[$office->type] ?? 'bg-gray-500 text-white' }}">
                                {{ $office->type_label }}
                            </span>
                        </div>
                        @if($office->has_coordinates)
                        <div class="absolute top-3 right-3">
                            <span class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow" title="GPS tersedia">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition-colors">{{ $office->name }}</h3>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="line-clamp-2">{{ $office->address }}</span>
                            </p>
                            @if($office->phone)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $office->phone }}
                            </p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('about.offices.show', $office) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                            @if($office->has_coordinates)
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $office->latitude }},{{ $office->longitude }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-emerald-600 text-emerald-600 text-sm font-medium rounded-lg hover:bg-emerald-50 transition" title="Petunjuk Arah">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-gray-500 text-lg">Belum ada data kantor tersedia</p>
            </div>
            @endif
        </div>
    </section>
</x-frontend-layout>
