@props(['whyChooseUsSettings', 'whyChooseUs'])

<!-- Why Choose Us Section -->
@if($whyChooseUsSettings?->is_active)
<section class="py-20 bg-white relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl animate-float-delayed"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Content -->
            <div class="slide-in-left">
                <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold mb-4 animate-bounce-in">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mengapa Memilih Kami
                </div>
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900 mb-6">
                    {!! $whyChooseUsSettings->section_title ?? 'Bank Syariah yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">Terpercaya</span> dan Amanah' !!}
                </h2>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    {{ $whyChooseUsSettings->section_subtitle ?? 'Kami berkomitmen memberikan layanan perbankan syariah terbaik dengan prinsip kehati-hatian dan kepatuhan terhadap syariah Islam.' }}
                </p>

                <!-- Features List -->
                <div class="space-y-6">
                    @forelse($whyChooseUs as $index => $item)
                    <div class="flex items-start group fade-in-section" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="w-12 h-12 {{ $item->bg_class }} rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 {{ $item->hover_bg_class }} group-hover:scale-110 transition-all duration-300 shadow-sm">
                            @if($item->icon)
                            <x-optimized-image
                                src="{{ \App\Helpers\StorageHelper::url($item->icon) }}"
                                class="w-6 h-6 object-contain transition-all duration-300 filter group-hover:brightness-0 group-hover:invert"
                                alt="{{ $item->title }}"
                                width="24"
                                height="24"
                            />
                            @else
                            <svg class="w-6 h-6 {{ $item->text_class }} group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1 text-lg">{{ $item->title }}</h4>
                            <p class="text-gray-600 leading-relaxed">{{ $item->description }}</p>
                        </div>
                    </div>
                    @empty
                    <!-- Default content if no dynamic data -->
                    <div class="flex items-start group fade-in-section">
                        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 group-hover:bg-emerald-500 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Sesuai Prinsip Syariah</h4>
                            <p class="text-gray-600">Seluruh produk dan layanan kami telah disetujui oleh Dewan Pengawas Syariah</p>
                        </div>
                    </div>
                    <div class="flex items-start group fade-in-section delay-100">
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 group-hover:bg-blue-500 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Aman & Terpercaya</h4>
                            <p class="text-gray-600">Diawasi oleh OJK dan dijamin oleh LPS untuk keamanan dana Anda</p>
                        </div>
                    </div>
                    <div class="flex items-start group fade-in-section delay-200">
                        <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 group-hover:bg-amber-500 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Proses Cepat & Mudah</h4>
                            <p class="text-gray-600">Layanan yang efisien dengan proses yang transparan dan mudah dipahami</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Image -->
            <div class="relative slide-in-right">
                <div class="relative z-10">
                    @if($whyChooseUsSettings->section_image)
                    <x-optimized-image
                         src="{{ \App\Helpers\StorageHelper::url($whyChooseUsSettings->section_image) }}"
                         alt="Why Choose Us"
                         class="rounded-3xl shadow-2xl w-full max-w-md mx-auto lg:max-w-none"
                         aspect-ratio="4/5"
                    />
                    @else
                    <div class="rounded-3xl shadow-2xl w-full h-[600px] bg-gradient-to-br from-emerald-400 via-teal-500 to-emerald-600 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvc3ZnPg==')] opacity-20"></div>
                        <div class="text-center p-8">
                            <span class="block mb-4 p-4 bg-white/20 rounded-2xl w-24 h-24 mx-auto backdrop-blur-sm shadow-inner">
                                <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -bottom-6 -left-6 w-72 h-72 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-3xl -z-10 opacity-20 animate-pulse-glow"></div>
                <div class="absolute -top-6 -right-6 w-48 h-48 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-3xl -z-10 opacity-20"></div>

                <!-- Floating Card -->
            </div>
        </div>
    </div>
</section>
@endif
