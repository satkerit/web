<x-frontend-layout>
    <x-slot:title>{{ $career->title }} - Karir {{ config('app.name') }}</x-slot:title>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 py-12 md:py-16">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-4xl">
                <a href="{{ route('careers.index') }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white mb-4 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Karir
                </a>
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-white/20 text-white">
                        {{ $career->employment_type_label }}
                    </span>
                    @if($career->deadline)
                        @if($career->isExpired())
                            <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-red-500/80 text-white">
                                Lowongan Ditutup
                            </span>
                        @else
                            <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-yellow-500/80 text-white">
                                Deadline: {{ $career->deadline->format('d M Y') }}
                            </span>
                        @endif
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $career->title }}</h1>
                <div class="flex flex-wrap items-center gap-6 text-white/90">
                    @if($career->department)
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $career->department }}
                        </span>
                    @endif
                    @if($career->location)
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $career->location }}
                        </span>
                    @endif
                    @if($career->salary_range)
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $career->salary_range }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Description -->
                    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Deskripsi Pekerjaan
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($career->description)) !!}
                        </div>
                    </div>

                    <!-- Requirements -->
                    @if($career->requirements)
                    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Persyaratan
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($career->requirements)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Responsibilities -->
                    @if($career->responsibilities)
                    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Tanggung Jawab
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($career->responsibilities)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Benefits -->
                    @if($career->benefits)
                    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                            Benefit
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($career->benefits)) !!}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Apply Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tertarik dengan posisi ini?</h3>
                        @if(!$career->isExpired())
                            <p class="text-gray-600 text-sm mb-6">Kirimkan lamaran Anda melalui email dengan menyertakan CV dan dokumen pendukung lainnya.</p>
                            @php
                                $company = \App\Models\CompanyInfo::getInfo();
                            @endphp
                            <a href="mailto:{{ $company->email ?? 'hrd@example.com' }}?subject=Lamaran: {{ $career->title }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Kirim Lamaran
                            </a>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <svg class="w-10 h-10 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-red-700 font-medium">Lowongan ini sudah ditutup</p>
                                <p class="text-red-600 text-sm mt-1">Batas waktu lamaran telah berakhir</p>
                            </div>
                        @endif
                    </div>

                    <!-- Share -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Bagikan Lowongan</h3>
                        <div class="flex items-center gap-3">
                            <a href="https://wa.me/?text={{ urlencode($career->title . ' - ' . url()->current()) }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link berhasil disalin!')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Careers -->
            @if($relatedCareers->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Lowongan Lainnya</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedCareers as $related)
                        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                {{ $related->employment_type_label }}
                            </span>
                            <h3 class="text-lg font-bold text-gray-900 mt-3 mb-2">{{ $related->title }}</h3>
                            <div class="text-sm text-gray-600 mb-4">
                                @if($related->location)
                                    <span>{{ $related->location }}</span>
                                @endif
                            </div>
                            <a href="{{ route('careers.show', $related->slug) }}" class="text-emerald-600 font-semibold hover:text-emerald-700 inline-flex items-center gap-1">
                                Lihat Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>
</x-frontend-layout>
